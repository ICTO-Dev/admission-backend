<?php

namespace App\Services;

use App\Mail\ExamPermitMail;
use App\Models\ApplicantEducation;
use App\Models\ApplicantFamily;
use App\Models\ApplicantHealthEmergency;
use App\Models\ApplicantSibling;
use App\Models\Application;
use App\Models\Campus;
use App\Models\Course;
use App\Models\ExamSchedule;
use App\Models\SchoolYear;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ApplicationService
{
    /**
     * Get paginated applications with search & filter support.
     */
    public function paginate(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Application::with([
            'campus:id,name,aname',
            'schoolYear:id,name,is_active,status',
            'firstCourse:id,courseName',
            'secondCourse:id,courseName',
            'barangay:id,name',
            'examSchedule:id,exam_date,start_time,end_time,room_id',
            'examSchedule.room:id,room_name,venue_id',
            'family',
            'siblings',
            'education',
            'healthEmergency',
        ])->latest();

        if (!empty($filters['campus_id'])) {
            $query->where('campus_id', $filters['campus_id']);
        }

        if (!empty($filters['school_year_id'])) {
            $query->where('school_year_id', $filters['school_year_id']);
        } elseif (!empty($filters['school_year'])) {
            $syFilter = $filters['school_year'];
            if (is_numeric($syFilter)) {
                $query->where('school_year_id', (int) $syFilter);
            } else {
                $query->whereHas('schoolYear', function ($sq) use ($syFilter) {
                    $sq->where('name', $syFilter);
                });
            }
        }

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);
            $query->where(function ($q) use ($search) {
                $q->where('application_no', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('middle_name', 'like', "%{$search}%")
                  ->orWhere('email_address', 'like', "%{$search}%")
                  ->orWhere('lrn', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['status']) && $filters['status'] !== 'All') {
            if ($filters['status'] === 'Approved' || $filters['status'] === 'Approved for Exam') {
                $query->whereIn('status', ['Approved for Exam', 'Approved']);
            } else {
                $query->where('status', $filters['status']);
            }
        }

        return $query->paginate($perPage);
    }

    /**
     * Get status counts across applications.
     */
    public function getStatusCounts(?int $campusId = null, ?string $schoolYear = null): array
    {
        $query = Application::query();

        if ($campusId) {
            $query->where('campus_id', $campusId);
        }

        if ($schoolYear) {
            if (is_numeric($schoolYear)) {
                $query->where('school_year_id', (int) $schoolYear);
            } else {
                $query->whereHas('schoolYear', function ($sq) use ($schoolYear) {
                    $sq->where('name', $schoolYear);
                });
            }
        }

        return [
            'all' => (clone $query)->count(),
            'pending' => (clone $query)->where('status', 'Pending')->count(),
            'approved' => (clone $query)->whereIn('status', ['Approved for Exam', 'Approved'])->count(),
            'scheduled' => (clone $query)->where('status', 'Scheduled')->count(),
            'rejected' => (clone $query)->where('status', 'Rejected')->count(),
        ];
    }

    /**
     * Update application status (e.g. Approved for Exam, Rejected, Pending).
     */
    public function updateStatus(string $identifier, string $status, ?string $rejectionReason = null): Application
    {
        $application = $this->findByNoOrId($identifier);
        if (!$application) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException("Application record '{$identifier}' not found");
        }

        $application->status = $status;
        if ($status === 'Rejected') {
            $application->rejection_reason = $rejectionReason;
        } elseif ($status === 'Approved for Exam' || $status === 'Approved') {
            $application->rejection_reason = null;
        }
        $application->save();

        return $this->findByNoOrId($application->id);
    }

    /**
     * Assign exam schedule slot to an application.
     */
    public function assignExamSchedule(string $identifier, int|string $examScheduleId, ?string $course = null): Application
    {
        $application = $this->findByNoOrId($identifier);
        if (!$application) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException("Application record '{$identifier}' not found");
        }

        $schedule = ExamSchedule::with(['room.venue', 'campus'])->withCount('applications')->find($examScheduleId);
        if (!$schedule) {
            throw new \InvalidArgumentException('Selected exam schedule slot not found.');
        }

        $totalSeats = $schedule->room?->total_seat ?? $schedule->max_capacity ?? 30;
        if ($schedule->applications_count >= $totalSeats || $schedule->status === 'Full') {
            $roomName = $schedule->room?->room_name ?? 'Room';
            throw new \RuntimeException("Automated Conflict Blocked: Room '{$roomName}' is at full capacity ({$schedule->applications_count}/{$totalSeats} seats). No more applicants can be assigned to this schedule.");
        }

        $application->exam_schedule_id = $schedule->id;
        $application->status = 'Scheduled';

        if ($course) {
            $courseObj = Course::where('courseName', $course)->orWhere('id', $course)->first();
            if ($courseObj) {
                $application->course_1_id = $courseObj->id;
            }
        }

        $application->save();

        // Calculate applicant seat number in this room schedule
        $seatNo = Application::where('exam_schedule_id', $schedule->id)
            ->where('id', '<=', $application->id)
            ->count();
        if ($seatNo === 0) {
            $seatNo = 1;
        }

        // Send confirmation and permit email notification to applicant
        if (!empty($application->email_address)) {
            try {
                $coordinator = auth('api')->user() ?? auth()->user();
                Mail::to($application->email_address)->send(
                    new ExamPermitMail($application, $schedule, $coordinator, $seatNo)
                );
            } catch (\Throwable $e) {
                Log::error("Failed to send exam permit email to [{$application->email_address}]: " . $e->getMessage());
            }
        }

        return $this->findByNoOrId($application->id);
    }

    /**
     * Find an application by its Application Number (e.g. APP-2026-0001) or database ID.
     */
    public function findByNoOrId(string $identifier): ?Application
    {
        return Application::with([
            'campus',
            'schoolYear:id,name,is_active,status',
            'firstCourse',
            'secondCourse',
            'barangay',
            'examSchedule.room.venue',
            'family',
            'siblings',
            'education',
            'healthEmergency',
        ])
        ->where('application_no', $identifier)
        ->orWhere('id', $identifier)
        ->first();
    }

    /**
     * Execute atomic creation of application records in database.
     */
    public function create(array $data): Application
    {
        return DB::transaction(function () use ($data) {
            // 1. Generate unique Application Number with pessimistic locking
            $year = date('Y');
            $latestApp = Application::whereYear('created_at', $year)
                ->orderByDesc('id')
                ->lockForUpdate()
                ->first();

            $nextSequence = 1;
            if ($latestApp && preg_match('/APP-\d{4}-(\d+)/', $latestApp->application_no, $matches)) {
                $nextSequence = intval($matches[1]) + 1;
            }
            $applicationNo = sprintf('APP-%s-%04d', $year, $nextSequence);

            // 2. Handle Photo Storage (Decode Base64 to disk)
            $photoPath = null;
            if (!empty($data['photoUrl']) && str_starts_with($data['photoUrl'], 'data:image')) {
                $photoPath = $this->storeBase64Photo($data['photoUrl'], $applicationNo);
            } else {
                $photoPath = $data['photoUrl'] ?? null;
            }

            // 3. Resolve Campus, Course, and Barangay foreign keys
            $campusId = !empty($data['campusId']) ? (int) $data['campusId'] : null;
            if (!$campusId && !empty($data['campus'])) {
                $campusObj = Campus::where('name', $data['campus'])
                    ->orWhere('aname', $data['campus'])
                    ->first();
                $campusId = $campusObj?->id;
            }

            $course1Id = !empty($data['course1Id']) ? (int) $data['course1Id'] : null;
            if (!$course1Id && !empty($data['courseApplied1st'])) {
                $course1Id = Course::where('courseName', $data['courseApplied1st'])->value('id');
            }

            $course2Id = !empty($data['course2Id']) ? (int) $data['course2Id'] : null;
            if (!$course2Id && !empty($data['courseApplied2nd'])) {
                $course2Id = Course::where('courseName', $data['courseApplied2nd'])->value('id');
            }

            $barangayId = !empty($data['barangayId']) ? (string) $data['barangayId'] : (!empty($data['barangay_id']) ? (string) $data['barangay_id'] : null);

            // Resolve open/active School Year
            $openSchoolYear = null;
            if (!empty($data['school_year_id'])) {
                $openSchoolYear = SchoolYear::find($data['school_year_id']);
            }

            if (!$openSchoolYear) {
                $openSchoolYear = SchoolYear::where('status', 'Open')->where('is_active', true)->first()
                               ?? SchoolYear::where('status', 'Open')->first()
                               ?? SchoolYear::where('is_active', true)->first();
            }

            if (!$openSchoolYear) {
                throw new \RuntimeException('Admissions are currently closed. No open academic school year is available.');
            }

            // 4. Create Main Application Record
            $application = Application::create([
                'application_no' => $applicationNo,
                'school_year_id' => $openSchoolYear->id,
                'student_type' => $data['studentType'],
                'campus_id' => $campusId,
                'course_1_id' => $course1Id,
                'course_2_id' => $course2Id,
                'barangay_id' => $barangayId,
                'lrn' => $data['lrn'],
                'last_name' => $data['lastName'],
                'first_name' => $data['firstName'],
                'middle_name' => $data['middleName'] ?? null,
                'date_of_birth' => $data['dateOfBirth'],
                'age' => $data['age'],
                'sex' => $data['sex'],
                'civil_status' => $data['civilStatus'],
                'place_of_birth' => $data['placeOfBirth'],
                'religion' => $data['religion'],
                'nationality' => $data['nationality'],
                'present_address' => $data['presentAddress'],
                'permanent_address' => $data['permanentAddress'],
                'mobile_number' => $data['mobileNumber'],
                'email_address' => $data['emailAddress'],
                'photo_url' => $photoPath,
                'is_indigenous' => (bool) $data['isIndigenous'],
                'indigenous_group' => $data['indigenousGroup'] ?? null,
                'is_solo_parent' => (bool) $data['isSoloParent'],
                'status' => 'Pending',
            ]);

            // 5. Create Family Profile
            $father = $data['fatherProfile'] ?? [];
            $mother = $data['motherProfile'] ?? [];
            $spouse = $data['spouseProfile'] ?? [];

            ApplicantFamily::create([
                'application_id' => $application->id,
                // Father
                'father_name' => $father['fullName'] ?? null,
                'father_age' => !empty($father['age']) ? (int) $father['age'] : null,
                'father_birthplace' => $father['birthplace'] ?? null,
                'father_education' => $father['educationalAttainment'] ?? null,
                'father_contact' => $father['contactNumber'] ?? null,
                'father_occupation' => $father['occupation'] ?? null,
                'father_workplace' => $father['placeOfWork'] ?? null,
                'father_living_status' => $father['livingStatus'] ?? null,
                'father_cause_of_death' => $father['causeOfDeath'] ?? null,
                'father_living_with_family' => $father['livingWithFamily'] ?? null,
                // Mother
                'mother_name' => $mother['fullName'] ?? null,
                'mother_age' => !empty($mother['age']) ? (int) $mother['age'] : null,
                'mother_birthplace' => $mother['birthplace'] ?? null,
                'mother_education' => $mother['educationalAttainment'] ?? null,
                'mother_contact' => $mother['contactNumber'] ?? null,
                'mother_occupation' => $mother['occupation'] ?? null,
                'mother_workplace' => $mother['placeOfWork'] ?? null,
                'mother_living_status' => $mother['livingStatus'] ?? null,
                'mother_cause_of_death' => $mother['causeOfDeath'] ?? null,
                'mother_living_with_family' => $mother['livingWithFamily'] ?? null,
                // Spouse
                'spouse_name' => $spouse['fullName'] ?? null,
                'spouse_age' => !empty($spouse['age']) ? (int) $spouse['age'] : null,
                'spouse_birthplace' => $spouse['birthplace'] ?? null,
                'spouse_education' => $spouse['educationalAttainment'] ?? null,
                'spouse_contact' => $spouse['contactNumber'] ?? null,
                'spouse_occupation' => $spouse['occupation'] ?? null,
                'spouse_workplace' => $spouse['placeOfWork'] ?? null,
                'spouse_living_status' => $spouse['livingStatus'] ?? null,
                'spouse_living_with_family' => $spouse['livingWithFamily'] ?? null,
                'spouse_dependents' => !empty($data['numberOfDependents']) ? (int) $data['numberOfDependents'] : null,
                // Demographics
                'birth_order' => $data['birthOrder'] ?? null,
                'birth_order_other' => $data['birthOrderOther'] ?? null,
                'housingCondition' => $data['housingCondition'] ?? null,
                'family_monthly_income' => $data['familyMonthlyIncome'] ?? null,
                'language_spoken' => $data['languageSpoken'] ?? null,
            ]);

            // 6. Create Siblings
            if (!empty($data['siblings']) && is_array($data['siblings'])) {
                foreach ($data['siblings'] as $sib) {
                    if (!empty($sib['name'])) {
                        ApplicantSibling::create([
                            'application_id' => $application->id,
                            'full_name' => $sib['name'],
                            'age' => !empty($sib['age']) ? (int) $sib['age'] : null,
                            'sex' => $sib['sex'] ?? null,
                            'civil_status' => $sib['civilStatus'] ?? null,
                            'educational_attainment' => $sib['educationalAttainment'] ?? null,
                        ]);
                    }
                }
            }

            // 7. Create Educational Background
            $elem = $data['elementary'] ?? [];
            $jhs = $data['juniorHigh'] ?? [];
            $shs = $data['seniorHigh'] ?? [];
            $coll = $data['college'] ?? [];

            ApplicantEducation::create([
                'application_id' => $application->id,
                'elem_name' => $elem['schoolName'] ?? null,
                'elem_grad_year' => $elem['yearGraduated'] ?? null,
                'elem_address' => $elem['address'] ?? null,
                'elem_awards' => $elem['awardsHonors'] ?? null,

                'jhs_name' => $jhs['schoolName'] ?? null,
                'jhs_grad_year' => $jhs['yearGraduated'] ?? null,
                'jhs_address' => $jhs['address'] ?? null,
                'jhs_awards' => $jhs['awardsHonors'] ?? null,

                'shs_name' => $shs['schoolName'] ?? null,
                'shs_grad_year' => $shs['yearGraduated'] ?? null,
                'shs_address' => $shs['address'] ?? null,
                'shs_track' => $shs['trackStrand'] ?? null,
                'shs_awards' => $shs['awardsHonors'] ?? null,
                'shs_avg_g11' => $shs['gwaG11'] ?? null,
                'shs_avg_g12' => $shs['gwaG12'] ?? null,

                'coll_name' => $coll['schoolName'] ?? null,
                'coll_years' => $coll['inclusiveYears'] ?? null,
                'coll_address' => $coll['address'] ?? null,
                'coll_course' => $coll['course'] ?? null,
                'coll_gwa' => $coll['gwa'] ?? null,
                'coll_awards' => $coll['awardsHonors'] ?? null,

                'first_gen_student' => isset($data['firstGenerationStudent']) ? (bool) $data['firstGenerationStudent'] : null,
                'family_college_count' => isset($data['familyCollegeGraduatesCount']) ? (string) $data['familyCollegeGraduatesCount'] : null,
                'future_outlook' => $data['futureOutlook'] ?? null,
            ]);

            // 8. Create Health & Emergency Details
            $emg = $data['emergencyContact'] ?? [];
            ApplicantHealthEmergency::create([
                'application_id' => $application->id,
                'pwd_status' => (bool) ($data['pwdStatus'] ?? false),
                'pwd_specs' => !empty($data['pwdStatus']) ? ($data['pwdSpecs'] ?? null) : null,
                'hospitalized_status' => (bool) ($data['hospitalizedStatus'] ?? false),
                'hospitalized_reasons' => !empty($data['hospitalizedStatus']) ? ($data['hospitalizedReasons'] ?? null) : null,
                'emergency_name' => $emg['name'] ?? '',
                'emergency_relation' => $emg['relation'] ?? '',
                'emergency_contact' => $emg['contactNo'] ?? '',
                'emergency_address' => $emg['address'] ?? '',
            ]);

            return $application->load([
                'schoolYear',
                'campus',
                'firstCourse',
                'secondCourse',
                'barangay',
                'family',
                'siblings',
                'education',
                'healthEmergency',
            ]);
        });
    }

    /**
     * Decode and store base64 photo to public storage disk.
     */
    protected function storeBase64Photo(string $base64String, string $appNo): string
    {
        $extension = 'jpg';
        if (preg_match('/^data:image\/(\w+);base64,/', $base64String, $match)) {
            $extension = strtolower($match[1]);
            $base64String = substr($base64String, strpos($base64String, ',') + 1);
        }

        $decoded = base64_decode($base64String);
        $fileName = 'applicant_' . Str::slug($appNo) . '_' . Str::random(8) . '.' . $extension;
        $filePath = 'applicants/photos/' . $fileName;

        $disk = config('filesystems.photo_disk', 'public');
        Storage::disk($disk)->put($filePath, $decoded, 'public');

        return $filePath;
    }
}

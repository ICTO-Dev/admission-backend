<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $family = $this->family;
        $edu = $this->education;
        $health = $this->healthEmergency;
        $siblings = $this->siblings;

        return [
            // Identifiers
            'id' => $this->application_no,
            'db_id' => $this->id,
            'applicationNo' => $this->application_no,
            'application_no' => $this->application_no,
            'schoolYear' => $this->school_year,
            'studentType' => $this->student_type,
            'campus' => $this->campus?->name ?? $this->campus_name,
            'campusId' => $this->campus_id,
            'courseApplied1st' => $this->firstCourse?->courseName ?? $this->course_applied_1st,
            'course1Id' => $this->course_1_id,
            'courseApplied2nd' => $this->secondCourse?->courseName ?? $this->course_applied_2nd,
            'course2Id' => $this->course_2_id,
            'barangayId' => $this->barangay_id,
            'barangay' => $this->barangay ? [
                'id' => $this->barangay->id,
                'name' => $this->barangay->name,
            ] : null,
            'status' => $this->status,
            'submissionDate' => $this->created_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),

            // Personal Information
            'lrn' => $this->lrn,
            'lastName' => $this->last_name,
            'firstName' => $this->first_name,
            'middleName' => $this->middle_name,
            'fullName' => $this->full_name,
            'dateOfBirth' => $this->date_of_birth,
            'age' => $this->age,
            'sex' => $this->sex,
            'civilStatus' => $this->civil_status,
            'placeOfBirth' => $this->place_of_birth,
            'religion' => $this->religion,
            'nationality' => $this->nationality,
            'presentAddress' => $this->present_address,
            'permanentAddress' => $this->permanent_address,
            'mobileNumber' => $this->mobile_number,
            'emailAddress' => $this->email_address,
            'photoUrl' => $this->photo_url,
            'isIndigenous' => (bool) $this->is_indigenous,
            'indigenousGroup' => $this->indigenous_group,
            'isSoloParent' => (bool) $this->is_solo_parent,

            // Family Profiles
            'fatherProfile' => [
                'fullName' => $family?->father_name ?? '',
                'age' => $family?->father_age ? (string) $family->father_age : '',
                'birthplace' => $family?->father_birthplace ?? '',
                'educationalAttainment' => $family?->father_education ?? '',
                'contactNumber' => $family?->father_contact ?? '',
                'occupation' => $family?->father_occupation ?? '',
                'placeOfWork' => $family?->father_workplace ?? '',
                'livingStatus' => $family?->father_living_status ?? 'Living',
                'causeOfDeath' => $family?->father_cause_of_death ?? '',
                'livingWithFamily' => $family?->father_living_with_family ?? 'Yes',
            ],
            'motherProfile' => [
                'fullName' => $family?->mother_name ?? '',
                'age' => $family?->mother_age ? (string) $family->mother_age : '',
                'birthplace' => $family?->mother_birthplace ?? '',
                'educationalAttainment' => $family?->mother_education ?? '',
                'contactNumber' => $family?->mother_contact ?? '',
                'occupation' => $family?->mother_occupation ?? '',
                'placeOfWork' => $family?->mother_workplace ?? '',
                'livingStatus' => $family?->mother_living_status ?? 'Living',
                'causeOfDeath' => $family?->mother_cause_of_death ?? '',
                'livingWithFamily' => $family?->mother_living_with_family ?? 'Yes',
            ],
            'spouseProfile' => [
                'fullName' => $family?->spouse_name ?? '',
                'age' => $family?->spouse_age ? (string) $family->spouse_age : '',
                'birthplace' => $family?->spouse_birthplace ?? '',
                'educationalAttainment' => $family?->spouse_education ?? '',
                'contactNumber' => $family?->spouse_contact ?? '',
                'occupation' => $family?->spouse_occupation ?? '',
                'placeOfWork' => $family?->spouse_workplace ?? '',
                'livingStatus' => $family?->spouse_living_status ?? 'Living',
                'livingWithFamily' => $family?->spouse_living_with_family ?? 'Yes',
            ],
            'numberOfDependents' => $family?->spouse_dependents ? (string) $family->spouse_dependents : '',
            'birthOrder' => $family?->birth_order ?? '',
            'birthOrderOther' => $family?->birth_order_other ?? '',
            'housingCondition' => $family?->housing_condition ?? '',
            'familyMonthlyIncome' => $family?->family_monthly_income ?? '',
            'languageSpoken' => $family?->language_spoken ?? '',

            // Siblings
            'siblings' => $siblings ? $siblings->map(function ($sib) {
                return [
                    'id' => $sib->id,
                    'name' => $sib->full_name,
                    'age' => $sib->age ? (string) $sib->age : '',
                    'sex' => $sib->sex ?? 'Male',
                    'civilStatus' => $sib->civil_status ?? 'Single',
                    'educationalAttainment' => $sib->educational_attainment ?? 'High School',
                ];
            })->values()->all() : [],

            // Educational Background
            'elementary' => [
                'schoolName' => $edu?->elem_name ?? '',
                'yearGraduated' => $edu?->elem_grad_year ?? '',
                'address' => $edu?->elem_address ?? '',
                'awardsHonors' => $edu?->elem_awards ?? '',
            ],
            'juniorHigh' => [
                'schoolName' => $edu?->jhs_name ?? '',
                'yearGraduated' => $edu?->jhs_grad_year ?? '',
                'address' => $edu?->jhs_address ?? '',
                'awardsHonors' => $edu?->jhs_awards ?? '',
            ],
            'seniorHigh' => [
                'schoolName' => $edu?->shs_name ?? '',
                'yearGraduated' => $edu?->shs_grad_year ?? '',
                'address' => $edu?->shs_address ?? '',
                'trackStrand' => $edu?->shs_track ?? '',
                'awardsHonors' => $edu?->shs_awards ?? '',
                'gwaG11' => $edu?->shs_avg_g11 ?? '',
                'gwaG12' => $edu?->shs_avg_g12 ?? '',
            ],
            'college' => [
                'schoolName' => $edu?->coll_name ?? '',
                'inclusiveYears' => $edu?->coll_years ?? '',
                'address' => $edu?->coll_address ?? '',
                'course' => $edu?->coll_course ?? '',
                'gwa' => $edu?->coll_gwa ?? '',
                'awardsHonors' => $edu?->coll_awards ?? '',
            ],
            'firstGenerationStudent' => (bool) ($edu?->first_gen_student ?? false),
            'familyCollegeGraduatesCount' => $edu?->family_college_count ?? '0',
            'futureOutlook' => $edu?->future_outlook ?? '',

            // Health & Emergency
            'pwdStatus' => (bool) ($health?->pwd_status ?? false),
            'pwdSpecs' => $health?->pwd_specs ?? '',
            'hospitalizedStatus' => (bool) ($health?->hospitalized_status ?? false),
            'hospitalizedReasons' => $health?->hospitalized_reasons ?? '',
            'emergencyContact' => [
                'name' => $health?->emergency_name ?? '',
                'relation' => $health?->emergency_relation ?? '',
                'contactNo' => $health?->emergency_contact ?? '',
                'address' => $health?->emergency_address ?? '',
            ],
        ];
    }
}

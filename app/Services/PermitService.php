<?php

namespace App\Services;

use App\Models\Application;
use App\Models\ExamSchedule;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPdfWrapper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class PermitService
{
    /**
     * Resolve applicant photo to a base64 Data URI for reliable DomPDF embedding.
     */
    public function resolvePhotoBase64(?string $photoUrl): ?string
    {
        if (empty($photoUrl)) {
            return null;
        }

        if (str_starts_with($photoUrl, 'data:image')) {
            return $photoUrl;
        }

        $rawPath = preg_replace('#^https?://[^/]+/#', '', $photoUrl);
        $rawPath = ltrim($rawPath, '/');
        $relativePath = preg_replace('#^(storage/app/public/|app/public/|storage/)#', '', $rawPath);

        $diskName = config('filesystems.photo_disk', 'public');
        try {
            $photoDisk = Storage::disk($diskName);
            if ($photoDisk->exists($relativePath)) {
                $content = $photoDisk->get($relativePath);
                $ext = pathinfo($relativePath, PATHINFO_EXTENSION);
                return 'data:image/' . ($ext ?: 'jpeg') . ';base64,' . base64_encode($content);
            }
        } catch (\Throwable $e) {
            // Ignore disk connection failure and proceed to local fallback
        }

        $candidates = [
            Storage::disk('public')->path($relativePath),
            public_path('storage/' . $relativePath),
            storage_path('app/public/' . $relativePath),
            public_path($rawPath),
            base_path($rawPath),
        ];

        foreach ($candidates as $cand) {
            if (file_exists($cand) && is_file($cand)) {
                $ext = pathinfo($cand, PATHINFO_EXTENSION);
                return 'data:image/' . ($ext ?: 'jpeg') . ';base64,' . base64_encode(file_get_contents($cand));
            }
        }

        return null;
    }

    /**
     * Load official CBSUA Seal logo as a base64 Data URI.
     */
    public function getCbsuaLogoBase64(): string
    {
        $logoPath = public_path('images/cbsua.png');

        if (file_exists($logoPath) && is_file($logoPath)) {
            return 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        }

        return '';
    }

    /**
     * Build unified permit dataset for PDF rendering and email body.
     */
    public function buildPermitData(
        Application $application,
        ?ExamSchedule $schedule = null,
        ?User $coordinator = null,
        $seatNo = null
    ): array {
        $schedule = $schedule ?? $application->examSchedule;
        $batch = $schedule?->batch;
        $room = $schedule?->room;
        $venue = $room?->venue;
        $coordinator = $coordinator ?? auth('api')->user() ?? auth()->user();

        // Format applicant name
        $middleInitial = !empty($application->middle_name) ? ' ' . $application->middle_name : '';
        $formattedName = strtoupper(trim(($application->last_name ?? '') . ', ' . ($application->first_name ?? '') . $middleInitial));

        // Format dates
        $formattedDOB = !empty($application->date_of_birth)
            ? Carbon::parse($application->date_of_birth)->format('m/d/Y')
            : 'N/A';

        // Format address
        $addressParts = array_filter([
            $application->permanent_address,
            $application->barangay?->name,
            $application->barangay?->municipality?->name,
            $application->barangay?->municipality?->province?->name,
        ]);
        $address = !empty($addressParts) ? implode(', ', $addressParts) : ($application->present_address ?? 'N/A');

        // Schedule timing
        $examDateOnly = $batch?->date
            ? Carbon::parse($batch->date)->format('F d, Y (l)')
            : (!empty($schedule?->exam_date) ? Carbon::parse($schedule->exam_date)->format('F d, Y') : 'TBA');

        $examTimeOnly = ($schedule?->start_time && $schedule?->end_time)
            ? Carbon::parse($schedule->start_time)->format('h:i A') . ' - ' . Carbon::parse($schedule->end_time)->format('h:i A')
            : 'TBA';

        $batchName = $batch?->batch_name ?? ($schedule ? 'Batch ' . ($schedule->batch_id ?? '1') : 'Regular Batch');
        $venueName = $venue?->name ?? $venue?->venue_name ?? $schedule?->campus?->name ?? 'CBSUA Campus';
        $roomName = $room?->name ?? $room?->room_name ?? 'Designated Room';
        $effectiveSeatNo = $seatNo ?? $schedule?->seat_number ?? $application->id;

        $campusAddress = $schedule?->campus?->campus_address
            ?? $application->campus?->campus_address
            ?? 'San Jose, Pili, Camarines Sur 4418';

        $coordinatorName = $coordinator?->name ?? 'Admission Office';
        $coordinatorContact = $coordinator?->contact ?? $coordinator?->email ?? '(054) 871-5531 local 101';
        $coordinatorEmail = $coordinator?->email ?? 'admission@cbsua.edu.ph';

        $photoBase64 = $this->resolvePhotoBase64($application->photo_url);
        $cbsuaLogoBase64 = $this->getCbsuaLogoBase64();

        return [
            'application' => $application,
            'applicationNo' => $application->application_no,
            'printedName' => $formattedName,
            'dateOfBirth' => $formattedDOB,
            'age' => $application->age ?? 'N/A',
            'contactNo' => $application->mobile_number ?? 'N/A',
            'address' => $address,
            'photoBase64' => $photoBase64,
            'photoUrl' => $photoBase64,
            'cbsuaLogoBase64' => $cbsuaLogoBase64,
            'cbsuaLogoUrl' => $cbsuaLogoBase64 ?: url('images/cbsua.png'),
            'examDate' => "{$examDateOnly} ({$examTimeOnly})",
            'examDateOnly' => $examDateOnly,
            'examTimeOnly' => $examTimeOnly,
            'batchName' => $batchName,
            'venueName' => $venueName,
            'roomName' => $roomName,
            'seatNo' => $effectiveSeatNo,
            'campusAddress' => $campusAddress,
            'coordinatorName' => $coordinatorName,
            'coordinatorContact' => $coordinatorContact,
            'coordinatorEmail' => $coordinatorEmail,
        ];
    }

    /**
     * Generate pre-configured DomPDF object for official permit (ADM-FR-005).
     */
    public function generatePdf(
        Application $application,
        ?ExamSchedule $schedule = null,
        ?User $coordinator = null,
        $seatNo = null
    ): DomPdfWrapper {
        $data = $this->buildPermitData($application, $schedule, $coordinator, $seatNo);

        return Pdf::loadView('pdf.cat_permit', $data)
            ->setPaper('letter', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'Helvetica',
            ]);
    }
}

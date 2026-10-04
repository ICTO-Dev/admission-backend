<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Services\PermitService;
use Illuminate\Http\Request;

class PermitController extends Controller
{
    public function __construct(
        protected PermitService $permitService
    ) {}

    /**
     * Generate downloadable official CBSUA CAT Examination Permit (ADM-FR-005) as PDF.
     */
    public function showPermit(Request $request, string $applicationNo)
    {
        $application = Application::with([
            'campus',
            'schoolYear',
            'barangay.municipality.province',
            'examSchedule.room.venue',
            'examSchedule.batch',
            'examSchedule.campus',
        ])
        ->where('application_no', $applicationNo)
        ->orWhere('id', $applicationNo)
        ->firstOrFail();

        $schedule = $application->examSchedule;
        if (!$schedule) {
            abort(404, 'Examination schedule has not been assigned for this application yet.');
        }

        // Calculate applicant seat number in this room schedule
        $seatNo = Application::where('exam_schedule_id', $schedule->id)
            ->where('id', '<=', $application->id)
            ->count();
        if ($seatNo === 0) {
            $seatNo = 1;
        }

        $pdf = $this->permitService->generatePdf($application, $schedule, null, $seatNo);
        $fileName = "CBSUA_CAT_Permit_{$application->application_no}.pdf";

        // If ?stream=1, view directly in browser PDF reader, otherwise download file
        if ($request->query('stream') == 1) {
            return $pdf->stream($fileName);
        }

        return $pdf->download($fileName);
    }
}

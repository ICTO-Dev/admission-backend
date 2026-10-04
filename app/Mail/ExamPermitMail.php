<?php

namespace App\Mail;

use App\Models\Application;
use App\Models\ExamSchedule;
use App\Models\User;
use App\Services\PermitService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ExamPermitMail extends Mailable
{
    use Queueable, SerializesModels;

    public Application $application;
    public ExamSchedule $schedule;
    public ?User $coordinator;
    public int $seatNo;

    /**
     * Create a new message instance.
     */
    public function __construct(
        Application $application,
        ExamSchedule $schedule,
        ?User $coordinator = null,
        int $seatNo = 1
    ) {
        $this->application = $application;
        $this->schedule = $schedule;
        $this->coordinator = $coordinator;
        $this->seatNo = $seatNo;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'CBSUA CAT Examination Permit Schedule: ' . $this->application->application_no,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $app = $this->application;
        $app->loadMissing(['barangay.municipality.province', 'campus']);
        $this->schedule->loadMissing(['batch', 'room.venue', 'campus']);

        /** @var PermitService $permitService */
        $permitService = app(PermitService::class);
        $data = $permitService->buildPermitData($app, $this->schedule, $this->coordinator, $this->seatNo);

        $applicantName = trim("{$app->first_name} {$app->last_name}") ?: $app->application_no;
        $frontendUrl = rtrim(config('app.frontend_url', env('FRONTEND_URL', 'https://admission.cbsua.edu.ph')), '/');
        $backendUrl = rtrim(config('app.url', env('APP_URL', 'http://localhost:8000')), '/');

        if (($backendUrl === 'http://localhost' || empty(parse_url($backendUrl, PHP_URL_PORT))) && env('APP_ENV') === 'local') {
            $backendUrl = 'http://localhost:8000';
        }

        // Additional email-specific variables
        $data['currentDate'] = Carbon::now()->format('M d, Y');
        $data['toName'] = $applicantName;
        $data['permitUrl'] = "{$frontendUrl}/status/{$app->application_no}";
        $data['directPermitUrl'] = "{$backendUrl}/admission/pdf-permit/{$app->application_no}";
        $data['studentFormUrl'] = "{$frontendUrl}/status/{$app->application_no}";

        return new Content(
            view: 'emails.exam_permit',
            with: $data,
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        try {
            /** @var PermitService $permitService */
            $permitService = app(PermitService::class);
            $pdf = $permitService->generatePdf($this->application, $this->schedule, $this->coordinator, $this->seatNo);
            $pdfOutput = $pdf->output();

            return [
                Attachment::fromData(
                    fn () => $pdfOutput,
                    "CBSUA_CAT_Permit_{$this->application->application_no}.pdf"
                )->withMime('application/pdf'),
            ];
        } catch (\Throwable $e) {
            Log::error("Failed to generate PDF attachment for permit: " . $e->getMessage());
            return [];
        }
    }
}

<?php

namespace App\Actions\ExamSchedule;

use App\Models\ExamSchedule;
use App\Services\ExamScheduleService;

class DeleteExamScheduleAction
{
    public function __construct(protected ExamScheduleService $examScheduleService) {}

    public function execute(ExamSchedule $schedule): bool
    {
        return $this->examScheduleService->delete($schedule);
    }
}

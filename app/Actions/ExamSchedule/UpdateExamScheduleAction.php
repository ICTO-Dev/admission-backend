<?php

namespace App\Actions\ExamSchedule;

use App\Models\ExamSchedule;
use App\Services\ExamScheduleService;

class UpdateExamScheduleAction
{
    public function __construct(protected ExamScheduleService $examScheduleService) {}

    public function execute(ExamSchedule $schedule, array $data): ExamSchedule
    {
        return $this->examScheduleService->update($schedule, $data);
    }
}

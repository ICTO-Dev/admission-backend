<?php

namespace App\Actions\ExamSchedule;

use App\Models\ExamSchedule;
use App\Services\ExamScheduleService;

class FindExamScheduleAction
{
    public function __construct(protected ExamScheduleService $examScheduleService) {}

    public function execute(int|string $id): ?ExamSchedule
    {
        return $this->examScheduleService->findById($id);
    }
}

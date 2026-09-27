<?php

namespace App\Actions\ExamSchedule;

use App\Models\ExamSchedule;
use App\Services\ExamScheduleService;

class StoreExamScheduleAction
{
    public function __construct(protected ExamScheduleService $examScheduleService) {}

    public function execute(array $data): ExamSchedule
    {
        return $this->examScheduleService->create($data);
    }
}

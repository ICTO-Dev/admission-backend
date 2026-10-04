<?php

namespace App\Actions\ExamSchedule;

use App\Services\ExamScheduleService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class FetchExamSchedulesAction
{
    public function __construct(protected ExamScheduleService $examScheduleService) {}

    public function execute(array $filters = []): Collection|LengthAwarePaginator
    {
        return $this->examScheduleService->getAll($filters);
    }
}

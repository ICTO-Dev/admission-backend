<?php

namespace App\Actions\Application;

use App\Services\ApplicationService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class FetchApplicationsAction
{
    public function __construct(
        protected ApplicationService $applicationService
    ) {}

    public function execute(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->applicationService->paginate($filters, $perPage);
    }
}

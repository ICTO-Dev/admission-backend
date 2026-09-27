<?php

namespace App\Actions\SchoolYear;

use App\Services\SchoolYearService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class FetchSchoolYearsAction
{
    public function __construct(protected SchoolYearService $schoolYearService) {}

    public function execute(array $filters = []): Collection|LengthAwarePaginator
    {
        return $this->schoolYearService->getAll($filters);
    }
}

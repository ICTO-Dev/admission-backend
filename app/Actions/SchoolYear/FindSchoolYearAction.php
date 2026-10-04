<?php

namespace App\Actions\SchoolYear;

use App\Models\SchoolYear;
use App\Services\SchoolYearService;

class FindSchoolYearAction
{
    public function __construct(protected SchoolYearService $schoolYearService) {}

    public function execute(int|string $id): ?SchoolYear
    {
        return $this->schoolYearService->findById($id);
    }
}

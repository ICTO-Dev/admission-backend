<?php

namespace App\Actions\SchoolYear;

use App\Models\SchoolYear;
use App\Services\SchoolYearService;

class StoreSchoolYearAction
{
    public function __construct(protected SchoolYearService $schoolYearService) {}

    public function execute(array $data): SchoolYear
    {
        return $this->schoolYearService->create($data);
    }
}

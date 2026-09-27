<?php

namespace App\Actions\SchoolYear;

use App\Models\SchoolYear;
use App\Services\SchoolYearService;

class UpdateSchoolYearAction
{
    public function __construct(protected SchoolYearService $schoolYearService) {}

    public function execute(SchoolYear $schoolYear, array $data): SchoolYear
    {
        return $this->schoolYearService->update($schoolYear, $data);
    }
}

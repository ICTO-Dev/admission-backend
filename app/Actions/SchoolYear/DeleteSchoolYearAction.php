<?php

namespace App\Actions\SchoolYear;

use App\Models\SchoolYear;
use App\Services\SchoolYearService;

class DeleteSchoolYearAction
{
    public function __construct(protected SchoolYearService $schoolYearService) {}

    public function execute(SchoolYear $schoolYear): bool
    {
        return $this->schoolYearService->delete($schoolYear);
    }
}

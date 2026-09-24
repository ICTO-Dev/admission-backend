<?php

namespace App\Actions\Campus;

use App\Models\Campus;
use App\Services\CampusService;

class DeleteCampusAction
{
    public function __construct(protected CampusService $campusService) {}

    public function execute(Campus $campus): bool
    {
        return $this->campusService->delete($campus);
    }
}

<?php

namespace App\Actions\Campus;

use App\Models\Campus;
use App\Services\CampusService;

class UpdateCampusAction
{
    public function __construct(protected CampusService $campusService) {}

    public function execute(Campus $campus, array $data): Campus
    {
        return $this->campusService->update($campus, $data);
    }
}

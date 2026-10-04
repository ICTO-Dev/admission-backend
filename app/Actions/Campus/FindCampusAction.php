<?php

namespace App\Actions\Campus;

use App\Models\Campus;
use App\Services\CampusService;

class FindCampusAction
{
    public function __construct(protected CampusService $campusService) {}

    public function execute(int|string $id): ?Campus
    {
        return $this->campusService->findById($id);
    }
}

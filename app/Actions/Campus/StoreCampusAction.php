<?php

namespace App\Actions\Campus;

use App\Models\Campus;
use App\Services\CampusService;

class StoreCampusAction
{
    public function __construct(protected CampusService $campusService) {}

    public function execute(array $data): Campus
    {
        return $this->campusService->create($data);
    }
}

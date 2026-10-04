<?php

namespace App\Actions\Application;

use App\Models\Application;
use App\Services\ApplicationService;

class StoreApplicationAction
{
    public function __construct(
        protected ApplicationService $applicationService
    ) {}

    public function execute(array $data): Application
    {
        return $this->applicationService->create($data);
    }
}

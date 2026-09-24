<?php

namespace App\Actions\Application;

use App\Models\Application;
use App\Services\ApplicationService;

class FindApplicationAction
{
    public function __construct(
        protected ApplicationService $applicationService
    ) {}

    public function execute(string $identifier): ?Application
    {
        return $this->applicationService->findByNoOrId($identifier);
    }
}

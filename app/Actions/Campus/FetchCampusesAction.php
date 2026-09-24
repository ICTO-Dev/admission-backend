<?php

namespace App\Actions\Campus;

use App\Services\CampusService;
use Illuminate\Database\Eloquent\Collection;

class FetchCampusesAction
{
    public function __construct(protected CampusService $campusService) {}

    public function execute(bool $withCourses = false): Collection
    {
        return $this->campusService->getAll($withCourses);
    }
}

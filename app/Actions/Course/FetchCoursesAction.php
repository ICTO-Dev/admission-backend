<?php

namespace App\Actions\Course;

use App\Services\CourseService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class FetchCoursesAction
{
    public function __construct(protected CourseService $courseService) {}

    public function execute(array $filters = []): Collection|LengthAwarePaginator
    {
        return $this->courseService->getAll($filters);
    }
}

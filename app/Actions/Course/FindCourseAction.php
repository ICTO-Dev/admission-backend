<?php

namespace App\Actions\Course;

use App\Models\Course;
use App\Services\CourseService;

class FindCourseAction
{
    public function __construct(protected CourseService $courseService) {}

    public function execute(int|string $id): ?Course
    {
        return $this->courseService->findById($id);
    }
}

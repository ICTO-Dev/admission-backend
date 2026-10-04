<?php

namespace App\Actions\Course;

use App\Models\Course;
use App\Services\CourseService;

class StoreCourseAction
{
    public function __construct(protected CourseService $courseService) {}

    public function execute(array $data): Course
    {
        return $this->courseService->create($data);
    }
}

<?php

namespace App\Actions\Course;

use App\Models\Course;
use App\Services\CourseService;

class DeleteCourseAction
{
    public function __construct(protected CourseService $courseService) {}

    public function execute(Course $course): bool
    {
        return $this->courseService->delete($course);
    }
}

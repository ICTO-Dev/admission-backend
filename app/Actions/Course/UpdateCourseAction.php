<?php

namespace App\Actions\Course;

use App\Models\Course;
use App\Services\CourseService;

class UpdateCourseAction
{
    public function __construct(protected CourseService $courseService) {}

    public function execute(Course $course, array $data): Course
    {
        return $this->courseService->update($course, $data);
    }
}

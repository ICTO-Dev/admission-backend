<?php

namespace App\Services;

use App\Models\Course;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CourseService
{
    public function getAll(array $filters = []): Collection|LengthAwarePaginator
    {
        $query = Course::with('campus');

        if (isset($filters['campus_id']) && $filters['campus_id'] !== null) {
            $query->where('campus_id', $filters['campus_id']);
        }

        if (isset($filters['status']) && $filters['status'] !== null) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['college']) && $filters['college'] !== null) {
            $query->where('college', $filters['college']);
        }

        if (isset($filters['is_open_program']) && $filters['is_open_program'] !== null) {
            $query->where('is_open_program', (bool) $filters['is_open_program']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where('courseName', 'like', "%{$search}%");
        }

        if (!empty($filters['paginate'])) {
            return $query->orderBy('courseName', 'asc')->paginate($filters['per_page'] ?? 15);
        }

        return $query->orderBy('courseName', 'asc')->get();
    }

    public function findById(int|string $id): ?Course
    {
        return Course::with('campus')->find($id);
    }

    public function create(array $data): Course
    {
        $course = Course::create($data);
        $course->load('campus');
        return $course;
    }

    public function update(Course $course, array $data): Course
    {
        $course->update($data);
        $course->load('campus');
        return $course;
    }

    public function delete(Course $course): bool
    {
        return $course->delete();
    }
}

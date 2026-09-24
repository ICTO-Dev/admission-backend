<?php

namespace App\Services;

use App\Models\Campus;
use Illuminate\Database\Eloquent\Collection;

class CampusService
{
    public function getAll(bool $withCourses = false): Collection
    {
        $query = Campus::query();

        if ($withCourses) {
            $query->with('courses');
        } else {
            $query->withCount('courses');
        }

        return $query->orderBy('name', 'asc')->get();
    }

    public function findById(int|string $id): ?Campus
    {
        return Campus::with('courses')->find($id);
    }

    public function create(array $data): Campus
    {
        return Campus::create($data);
    }

    public function update(Campus $campus, array $data): Campus
    {
        $campus->update($data);
        return $campus;
    }

    public function delete(Campus $campus): bool
    {
        return $campus->delete();
    }
}

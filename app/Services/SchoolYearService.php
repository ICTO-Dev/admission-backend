<?php

namespace App\Services;

use App\Models\SchoolYear;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class SchoolYearService
{
    public function getAll(array $filters = []): Collection|LengthAwarePaginator
    {
        $query = SchoolYear::select(['id', 'name', 'is_active', 'status', 'user_id', 'created_at', 'updated_at'])
            ->with(['creator:id,name,email'])
            ->withCount(['batches', 'examSchedules']);

        if (isset($filters['status']) && $filters['status'] !== null) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== null) {
            $query->where('is_active', (bool) $filters['is_active']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where('name', 'like', "%{$search}%");
        }

        $query->orderBy('name', 'desc');

        if (!empty($filters['paginate'])) {
            return $query->paginate($filters['per_page'] ?? 25);
        }

        return $query->get();
    }

    public function findById(int|string $id): ?SchoolYear
    {
        return SchoolYear::select(['id', 'name', 'is_active', 'status', 'user_id', 'created_at', 'updated_at'])
            ->with(['creator:id,name,email'])
            ->withCount(['batches', 'examSchedules'])
            ->find($id);
    }

    protected function getAuthUser(): ?\App\Models\User
    {
        return auth('api')->user() ?? request()->user('api') ?? request()->user() ?? auth()->user();
    }

    public function create(array $data): SchoolYear
    {
        $user = $this->getAuthUser();

        if (empty($data['user_id']) && $user?->id) {
            $data['user_id'] = $user->id;
        }

        // If newly created school year is set as active, optionally deactivate other school years
        if (!empty($data['is_active'])) {
            SchoolYear::where('is_active', true)->update(['is_active' => false]);
        }

        return SchoolYear::create($data);
    }

    public function update(SchoolYear $schoolYear, array $data): SchoolYear
    {
        if (isset($data['is_active']) && $data['is_active']) {
            SchoolYear::where('id', '!=', $schoolYear->id)->update(['is_active' => false]);
        }

        $schoolYear->update($data);
        return $schoolYear;
    }

    public function delete(SchoolYear $schoolYear): bool
    {
        return $schoolYear->delete();
    }
}

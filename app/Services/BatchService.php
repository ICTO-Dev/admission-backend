<?php

namespace App\Services;

use App\Models\Batch;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class BatchService
{
    protected function getAuthUser(): ?\App\Models\User
    {
        return auth('api')->user() ?? request()->user('api') ?? request()->user() ?? auth()->user();
    }

    public function getAll(array $filters = []): Collection|LengthAwarePaginator
    {
        $query = Batch::select(['id', 'campus_id', 'school_year_id', 'batch_name', 'status', 'user_id', 'created_at', 'updated_at'])
            ->with([
                'campus:id,name,aname',
                'schoolYear:id,name,is_active,status',
                'creator:id,name,email'
            ])
            ->withCount('examSchedules');

        // Campus isolation
        $user = $this->getAuthUser();
        $campusId = $filters['campus_id'] ?? $user?->campus_id;
        if (!empty($campusId)) {
            $query->where('campus_id', $campusId);
        }

        if (!empty($filters['school_year_id'])) {
            $query->where('school_year_id', $filters['school_year_id']);
        }

        if (isset($filters['status']) && $filters['status'] !== null) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where('batch_name', 'like', "%{$search}%");
        }

        $query->orderBy('id', 'desc');

        $shouldPaginate = false;
        if (isset($filters['all']) && filter_var($filters['all'], FILTER_VALIDATE_BOOLEAN)) {
            $shouldPaginate = false;
        } elseif (isset($filters['paginate'])) {
            $shouldPaginate = filter_var($filters['paginate'], FILTER_VALIDATE_BOOLEAN);
        } elseif (isset($filters['page']) || isset($filters['per_page'])) {
            $shouldPaginate = true;
        }

        if ($shouldPaginate) {
            $perPage = isset($filters['per_page']) ? (int) $filters['per_page'] : 15;
            return $query->paginate($perPage);
        }

        return $query->get();
    }

    public function findById(int|string $id): ?Batch
    {
        return Batch::select(['id', 'campus_id', 'school_year_id', 'batch_name', 'status', 'user_id', 'created_at', 'updated_at'])
            ->with([
                'campus:id,name,aname',
                'schoolYear:id,name,is_active,status',
                'creator:id,name,email'
            ])
            ->withCount('examSchedules')
            ->find($id);
    }

    public function create(array $data): Batch
    {
        $user = $this->getAuthUser();

        if (empty($data['campus_id']) && $user?->campus_id) {
            $data['campus_id'] = $user->campus_id;
        }

        if (empty($data['user_id']) && $user?->id) {
            $data['user_id'] = $user->id;
        }

        $batch = Batch::create($data);
        $batch->load(['campus', 'schoolYear', 'creator']);
        return $batch;
    }

    public function update(Batch $batch, array $data): Batch
    {
        $batch->update($data);
        $batch->load(['campus', 'schoolYear', 'creator']);
        return $batch;
    }

    public function delete(Batch $batch): bool
    {
        return $batch->delete();
    }
}

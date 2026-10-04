<?php

namespace App\Services;

use App\Models\Venue;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class VenueService
{
    protected function getAuthUser(): ?\App\Models\User
    {
        return auth('api')->user() ?? request()->user('api') ?? request()->user() ?? auth()->user();
    }

    public function getAll(array $filters = []): Collection|LengthAwarePaginator
    {
        $query = Venue::select(['id', 'campus_id', 'venue_name', 'description', 'user_id', 'created_at', 'updated_at'])
            ->with(['campus:id,name,aname', 'creator:id,name,email'])
            ->withCount('rooms');

        // Campus isolation: prioritize explicit filter, otherwise use authenticated user's campus
        $user = $this->getAuthUser();
        $campusId = $filters['campus_id'] ?? $user?->campus_id;
        if (!empty($campusId)) {
            $query->where('campus_id', $campusId);
        }

        if (!empty($filters['with_rooms'])) {
            $query->with(['rooms' => function ($q) {
                $q->select(['id', 'venue_id', 'room_name', 'total_seat', 'status'])
                  ->withCount('examSchedules');
            }]);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where('venue_name', 'like', "%{$search}%");
        }

        $query->orderBy('venue_name', 'asc');

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

    public function findById(int|string $id): ?Venue
    {
        return Venue::select(['id', 'campus_id', 'venue_name', 'description', 'user_id', 'created_at', 'updated_at'])
            ->with([
                'campus:id,name,aname',
                'creator:id,name,email',
                'rooms' => function ($q) {
                    $q->select(['id', 'venue_id', 'room_name', 'total_seat', 'status'])
                      ->withCount('examSchedules');
                }
            ])
            ->withCount('rooms')
            ->find($id);
    }

    public function create(array $data): Venue
    {
        $user = $this->getAuthUser();

        if (empty($data['campus_id']) && $user?->campus_id) {
            $data['campus_id'] = $user->campus_id;
        }

        if (empty($data['user_id']) && $user?->id) {
            $data['user_id'] = $user->id;
        }

        $venue = Venue::create($data);
        $venue->load(['campus', 'creator']);
        return $venue;
    }

    public function update(Venue $venue, array $data): Venue
    {
        $venue->update($data);
        $venue->load(['campus', 'creator', 'rooms']);
        return $venue;
    }

    public function delete(Venue $venue): bool
    {
        return $venue->delete();
    }
}

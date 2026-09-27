<?php

namespace App\Services;

use App\Models\Room;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class RoomService
{
    protected function getAuthUser(): ?\App\Models\User
    {
        return auth('api')->user() ?? request()->user('api') ?? request()->user() ?? auth()->user();
    }

    public function getAll(array $filters = []): Collection|LengthAwarePaginator
    {
        $query = Room::select(['id', 'venue_id', 'room_name', 'total_seat', 'status', 'user_id', 'created_at', 'updated_at'])
            ->with([
                'venue:id,campus_id,venue_name,description',
                'venue.campus:id,name,aname',
                'creator:id,name,email'
            ])
            ->withCount('examSchedules');

        if (!empty($filters['venue_id'])) {
            $query->where('venue_id', $filters['venue_id']);
        }

        // Campus isolation via venue
        $user = $this->getAuthUser();
        $campusId = $filters['campus_id'] ?? $user?->campus_id;
        if (!empty($campusId)) {
            $query->whereHas('venue', function ($q) use ($campusId) {
                $q->where('campus_id', $campusId);
            });
        }

        if (isset($filters['status']) && $filters['status'] !== null) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where('room_name', 'like', "%{$search}%");
        }

        $query->orderBy('room_name', 'asc');

        if (!empty($filters['paginate'])) {
            return $query->paginate($filters['per_page'] ?? 25);
        }

        return $query->get();
    }

    public function findById(int|string $id): ?Room
    {
        return Room::select(['id', 'venue_id', 'room_name', 'total_seat', 'status', 'user_id', 'created_at', 'updated_at'])
            ->with([
                'venue:id,campus_id,venue_name,description',
                'venue.campus:id,name,aname',
                'creator:id,name,email'
            ])
            ->withCount('examSchedules')
            ->find($id);
    }

    public function create(array $data): Room
    {
        $user = $this->getAuthUser();

        if (empty($data['user_id']) && $user?->id) {
            $data['user_id'] = $user->id;
        }

        $room = Room::create($data);
        $room->load(['venue.campus', 'creator']);
        return $room;
    }

    public function update(Room $room, array $data): Room
    {
        $room->update($data);
        $room->load(['venue.campus', 'creator']);
        return $room;
    }

    public function delete(Room $room): bool
    {
        return $room->delete();
    }
}

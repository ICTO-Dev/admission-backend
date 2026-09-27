<?php

namespace App\Services;

use App\Models\ExamSchedule;
use App\Models\Room;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ExamScheduleService
{
    protected function getAuthUser(): ?\App\Models\User
    {
        return auth('api')->user() ?? request()->user('api') ?? request()->user() ?? auth()->user();
    }

    public function getAll(array $filters = []): Collection|LengthAwarePaginator
    {
        $query = ExamSchedule::select([
            'id', 'campus_id', 'school_year_id', 'batch_id', 'room_id',
            'day_label', 'exam_date', 'start_time', 'end_time',
            'max_capacity', 'status', 'user_id', 'created_at', 'updated_at'
        ])
        ->with([
            'campus:id,name,aname',
            'schoolYear:id,name,is_active,status',
            'batch:id,campus_id,school_year_id,batch_name,status',
            'room:id,venue_id,room_name,total_seat,status',
            'room.venue:id,campus_id,venue_name,description',
            'creator:id,name,email',
        ])
        ->withCount('applications');

        // Campus isolation
        $user = $this->getAuthUser();
        $campusId = $filters['campus_id'] ?? $user?->campus_id;
        if (!empty($campusId)) {
            $query->where('campus_id', $campusId);
        }

        if (!empty($filters['school_year_id'])) {
            $query->where('school_year_id', $filters['school_year_id']);
        }

        if (!empty($filters['batch_id'])) {
            $query->where('batch_id', $filters['batch_id']);
        }

        if (!empty($filters['room_id'])) {
            $query->where('room_id', $filters['room_id']);
        }

        if (!empty($filters['exam_date'])) {
            $query->whereDate('exam_date', $filters['exam_date']);
        }

        if (isset($filters['status']) && $filters['status'] !== null) {
            $query->where('status', $filters['status']);
        }

        // For applicant scheduling: only slots with available seats and open status
        if (!empty($filters['available_only'])) {
            $query->where('status', 'Open')
                ->whereRaw('(SELECT COUNT(*) FROM applications WHERE applications.exam_schedule_id = exam_schedules.id) < exam_schedules.max_capacity');
        }

        $query->orderBy('exam_date', 'asc')
            ->orderBy('start_time', 'asc');

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

        $limit = isset($filters['limit']) ? (int) $filters['limit'] : 500;
        return $query->limit($limit)->get();
    }

    public function findById(int|string $id): ?ExamSchedule
    {
        return ExamSchedule::select([
            'id', 'campus_id', 'school_year_id', 'batch_id', 'room_id',
            'day_label', 'exam_date', 'start_time', 'end_time',
            'max_capacity', 'status', 'user_id', 'created_at', 'updated_at'
        ])
        ->with([
            'campus:id,name,aname',
            'schoolYear:id,name,is_active,status',
            'batch:id,campus_id,school_year_id,batch_name,status',
            'room:id,venue_id,room_name,total_seat,status',
            'room.venue:id,campus_id,venue_name,description',
            'creator:id,name,email',
        ])
        ->withCount('applications')
        ->find($id);
    }

    public function create(array $data): ExamSchedule
    {
        $user = $this->getAuthUser();

        if (empty($data['campus_id']) && $user?->campus_id) {
            $data['campus_id'] = $user->campus_id;
        }

        if (empty($data['user_id']) && $user?->id) {
            $data['user_id'] = $user->id;
        }

        // Capacity is naturally determined by the assigned room's total_seat
        if (!empty($data['room_id'])) {
            $room = Room::find($data['room_id']);
            $data['max_capacity'] = $room?->total_seat ?? $data['max_capacity'] ?? 30;
        } elseif (empty($data['max_capacity'])) {
            $data['max_capacity'] = 30;
        }

        $schedule = ExamSchedule::create($data);
        $schedule->load(['campus', 'schoolYear', 'batch', 'room.venue', 'creator']);
        return $schedule;
    }

    public function update(ExamSchedule $schedule, array $data): ExamSchedule
    {
        // Disallow editing once applicants are assigned to this schedule slot
        $currentEnrolled = $schedule->applications()->count();
        if ($currentEnrolled > 0) {
            throw new \RuntimeException("Cannot edit examination schedule because {$currentEnrolled} applicant(s) are already assigned to this slot.");
        }

        // If room is changed, re-sync max_capacity with the new room's total_seat
        if (!empty($data['room_id']) && $data['room_id'] != $schedule->room_id) {
            $room = Room::find($data['room_id']);
            if ($room) {
                $data['max_capacity'] = $room->total_seat;
            }
        }

        $schedule->update($data);
        $schedule->load(['campus', 'schoolYear', 'batch', 'room.venue', 'creator']);
        return $schedule;
    }

    public function delete(ExamSchedule $schedule): bool
    {
        // Disallow deleting once applicants are assigned to this schedule slot
        $currentEnrolled = $schedule->applications()->count();
        if ($currentEnrolled > 0) {
            throw new \RuntimeException("Cannot delete examination schedule because {$currentEnrolled} applicant(s) are already assigned to this slot.");
        }

        return $schedule->delete();
    }
}

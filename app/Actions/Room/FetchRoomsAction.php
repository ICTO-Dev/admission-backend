<?php

namespace App\Actions\Room;

use App\Services\RoomService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class FetchRoomsAction
{
    public function __construct(protected RoomService $roomService) {}

    public function execute(array $filters = []): Collection|LengthAwarePaginator
    {
        return $this->roomService->getAll($filters);
    }
}

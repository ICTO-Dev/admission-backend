<?php

namespace App\Actions\Room;

use App\Models\Room;
use App\Services\RoomService;

class FindRoomAction
{
    public function __construct(protected RoomService $roomService) {}

    public function execute(int|string $id): ?Room
    {
        return $this->roomService->findById($id);
    }
}

<?php

namespace App\Actions\Room;

use App\Models\Room;
use App\Services\RoomService;

class DeleteRoomAction
{
    public function __construct(protected RoomService $roomService) {}

    public function execute(Room $room): bool
    {
        return $this->roomService->delete($room);
    }
}

<?php

namespace App\Actions\Room;

use App\Models\Room;
use App\Services\RoomService;

class UpdateRoomAction
{
    public function __construct(protected RoomService $roomService) {}

    public function execute(Room $room, array $data): Room
    {
        return $this->roomService->update($room, $data);
    }
}

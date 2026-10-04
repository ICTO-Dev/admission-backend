<?php

namespace App\Actions\Room;

use App\Models\Room;
use App\Services\RoomService;

class StoreRoomAction
{
    public function __construct(protected RoomService $roomService) {}

    public function execute(array $data): Room
    {
        return $this->roomService->create($data);
    }
}

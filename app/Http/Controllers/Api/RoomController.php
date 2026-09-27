<?php

namespace App\Http\Controllers\Api;

use App\Actions\Room\DeleteRoomAction;
use App\Actions\Room\FetchRoomsAction;
use App\Actions\Room\FindRoomAction;
use App\Actions\Room\StoreRoomAction;
use App\Actions\Room\UpdateRoomAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Room\StoreRoomRequest;
use App\Http\Requests\Room\UpdateRoomRequest;
use App\Http\Resources\RoomResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RoomController extends Controller
{
    public function index(Request $request, FetchRoomsAction $action): AnonymousResourceCollection
    {
        $rooms = $action->execute($request->all());

        return RoomResource::collection($rooms);
    }

    public function store(StoreRoomRequest $request, StoreRoomAction $action): JsonResponse
    {
        $room = $action->execute($request->validated());

        return (new RoomResource($room))
            ->additional([
                'success' => true,
                'message' => 'Room created successfully',
            ])
            ->response()
            ->setStatusCode(201);
    }

    public function show(string $id, FindRoomAction $action): JsonResponse|RoomResource
    {
        $room = $action->execute($id);

        if (!$room) {
            return response()->json([
                'success' => false,
                'message' => 'Room not found',
            ], 404);
        }

        return new RoomResource($room);
    }

    public function update(UpdateRoomRequest $request, string $id, FindRoomAction $findAction, UpdateRoomAction $updateAction): JsonResponse|RoomResource
    {
        $room = $findAction->execute($id);

        if (!$room) {
            return response()->json([
                'success' => false,
                'message' => 'Room not found',
            ], 404);
        }

        $updated = $updateAction->execute($room, $request->validated());

        return (new RoomResource($updated))
            ->additional([
                'success' => true,
                'message' => 'Room updated successfully',
            ]);
    }

    public function destroy(string $id, FindRoomAction $findAction, DeleteRoomAction $deleteAction): JsonResponse
    {
        $room = $findAction->execute($id);

        if (!$room) {
            return response()->json([
                'success' => false,
                'message' => 'Room not found',
            ], 404);
        }

        $deleteAction->execute($room);

        return response()->json([
            'success' => true,
            'message' => 'Room deleted successfully',
        ]);
    }
}

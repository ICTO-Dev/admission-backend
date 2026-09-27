<?php

namespace App\Http\Controllers\Api;

use App\Actions\Venue\DeleteVenueAction;
use App\Actions\Venue\FetchVenuesAction;
use App\Actions\Venue\FindVenueAction;
use App\Actions\Venue\StoreVenueAction;
use App\Actions\Venue\UpdateVenueAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Venue\StoreVenueRequest;
use App\Http\Requests\Venue\UpdateVenueRequest;
use App\Http\Resources\VenueResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class VenueController extends Controller
{
    public function index(Request $request, FetchVenuesAction $action): AnonymousResourceCollection
    {
        $venues = $action->execute($request->all());

        return VenueResource::collection($venues);
    }

    public function store(StoreVenueRequest $request, StoreVenueAction $action): JsonResponse
    {
        $venue = $action->execute($request->validated());

        return (new VenueResource($venue))
            ->additional([
                'success' => true,
                'message' => 'Venue created successfully',
            ])
            ->response()
            ->setStatusCode(201);
    }

    public function show(string $id, FindVenueAction $action): JsonResponse|VenueResource
    {
        $venue = $action->execute($id);

        if (!$venue) {
            return response()->json([
                'success' => false,
                'message' => 'Venue not found',
            ], 404);
        }

        return new VenueResource($venue);
    }

    public function update(UpdateVenueRequest $request, string $id, FindVenueAction $findAction, UpdateVenueAction $updateAction): JsonResponse|VenueResource
    {
        $venue = $findAction->execute($id);

        if (!$venue) {
            return response()->json([
                'success' => false,
                'message' => 'Venue not found',
            ], 404);
        }

        $updated = $updateAction->execute($venue, $request->validated());

        return (new VenueResource($updated))
            ->additional([
                'success' => true,
                'message' => 'Venue updated successfully',
            ]);
    }

    public function destroy(string $id, FindVenueAction $findAction, DeleteVenueAction $deleteAction): JsonResponse
    {
        $venue = $findAction->execute($id);

        if (!$venue) {
            return response()->json([
                'success' => false,
                'message' => 'Venue not found',
            ], 404);
        }

        $deleteAction->execute($venue);

        return response()->json([
            'success' => true,
            'message' => 'Venue deleted successfully',
        ]);
    }
}

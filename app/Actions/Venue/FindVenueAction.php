<?php

namespace App\Actions\Venue;

use App\Models\Venue;
use App\Services\VenueService;

class FindVenueAction
{
    public function __construct(protected VenueService $venueService) {}

    public function execute(int|string $id): ?Venue
    {
        return $this->venueService->findById($id);
    }
}

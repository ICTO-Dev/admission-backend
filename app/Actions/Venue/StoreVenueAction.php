<?php

namespace App\Actions\Venue;

use App\Models\Venue;
use App\Services\VenueService;

class StoreVenueAction
{
    public function __construct(protected VenueService $venueService) {}

    public function execute(array $data): Venue
    {
        return $this->venueService->create($data);
    }
}

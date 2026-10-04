<?php

namespace App\Actions\Venue;

use App\Models\Venue;
use App\Services\VenueService;

class UpdateVenueAction
{
    public function __construct(protected VenueService $venueService) {}

    public function execute(Venue $venue, array $data): Venue
    {
        return $this->venueService->update($venue, $data);
    }
}

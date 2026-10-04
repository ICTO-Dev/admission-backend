<?php

namespace App\Actions\Venue;

use App\Models\Venue;
use App\Services\VenueService;

class DeleteVenueAction
{
    public function __construct(protected VenueService $venueService) {}

    public function execute(Venue $venue): bool
    {
        return $this->venueService->delete($venue);
    }
}

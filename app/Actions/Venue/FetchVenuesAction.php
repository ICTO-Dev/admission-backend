<?php

namespace App\Actions\Venue;

use App\Services\VenueService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class FetchVenuesAction
{
    public function __construct(protected VenueService $venueService) {}

    public function execute(array $filters = []): Collection|LengthAwarePaginator
    {
        return $this->venueService->getAll($filters);
    }
}

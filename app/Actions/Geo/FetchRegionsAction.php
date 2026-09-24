<?php

namespace App\Actions\Geo;

use App\Services\GeoService;
use Illuminate\Database\Eloquent\Collection;

class FetchRegionsAction
{
    public function __construct(protected GeoService $geoService) {}

    public function execute(): Collection
    {
        return $this->geoService->getRegions();
    }
}

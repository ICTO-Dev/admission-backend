<?php

namespace App\Actions\Geo;

use App\Services\GeoService;
use Illuminate\Database\Eloquent\Collection;

class FetchBarangaysAction
{
    public function __construct(protected GeoService $geoService) {}

    public function execute(?string $municipalityId = null): Collection
    {
        return $this->geoService->getBarangays($municipalityId);
    }
}

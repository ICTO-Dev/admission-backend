<?php

namespace App\Actions\Geo;

use App\Services\GeoService;
use Illuminate\Database\Eloquent\Collection;

class FetchProvincesAction
{
    public function __construct(protected GeoService $geoService) {}

    public function execute(?string $regionId = null): Collection
    {
        return $this->geoService->getProvinces($regionId);
    }
}

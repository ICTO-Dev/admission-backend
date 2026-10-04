<?php

namespace App\Actions\Geo;

use App\Services\GeoService;
use Illuminate\Database\Eloquent\Collection;

class FetchMunicipalitiesAction
{
    public function __construct(protected GeoService $geoService) {}

    public function execute(?string $provinceId = null): Collection
    {
        return $this->geoService->getMunicipalities($provinceId);
    }
}

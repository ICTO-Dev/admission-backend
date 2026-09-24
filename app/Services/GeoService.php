<?php

namespace App\Services;

use App\Models\Barangay;
use App\Models\Municipality;
use App\Models\Province;
use App\Models\Region;
use Illuminate\Database\Eloquent\Collection;

class GeoService
{
    /**
     * Get all regions
     */
    public function getRegions(): Collection
    {
        return Region::orderBy('name', 'asc')->get();
    }

    /**
     * Get provinces, optionally filtered by region_id
     */
    public function getProvinces(?string $regionId = null): Collection
    {
        $query = Province::query();

        if ($regionId) {
            $query->where('region_id', $regionId);
        }

        return $query->orderBy('name', 'asc')->get();
    }

    /**
     * Get municipalities, optionally filtered by province_id
     */
    public function getMunicipalities(?string $provinceId = null): Collection
    {
        $query = Municipality::query();

        if ($provinceId) {
            $query->where('province_id', $provinceId);
        }

        return $query->orderBy('name', 'asc')->get();
    }

    /**
     * Get barangays, optionally filtered by municipality_id
     */
    public function getBarangays(?string $municipalityId = null): Collection
    {
        $query = Barangay::query();

        if ($municipalityId) {
            $query->where('municipality_id', $municipalityId);
        }

        return $query->orderBy('name', 'asc')->get();
    }
}

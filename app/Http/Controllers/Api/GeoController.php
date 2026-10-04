<?php

namespace App\Http\Controllers\Api;

use App\Actions\Geo\FetchBarangaysAction;
use App\Actions\Geo\FetchMunicipalitiesAction;
use App\Actions\Geo\FetchProvincesAction;
use App\Actions\Geo\FetchRegionsAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Geo\BarangayResource;
use App\Http\Resources\Geo\MunicipalityResource;
use App\Http\Resources\Geo\ProvinceResource;
use App\Http\Resources\Geo\RegionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GeoController extends Controller
{
    /**
     * Display a listing of regions.
     */
    public function regions(FetchRegionsAction $action): AnonymousResourceCollection
    {
        $regions = $action->execute();
        return RegionResource::collection($regions);
    }

    /**
     * Display a listing of provinces, optionally filtered by region_id.
     */
    public function provinces(Request $request, FetchProvincesAction $action): AnonymousResourceCollection
    {
        $provinces = $action->execute($request->query('region_id'));
        return ProvinceResource::collection($provinces);
    }

    /**
     * Display a listing of municipalities, optionally filtered by province_id.
     */
    public function municipalities(Request $request, FetchMunicipalitiesAction $action): AnonymousResourceCollection
    {
        $municipalities = $action->execute($request->query('province_id'));
        return MunicipalityResource::collection($municipalities);
    }

    /**
     * Display a listing of barangays, optionally filtered by municipality_id.
     */
    public function barangays(Request $request, FetchBarangaysAction $action): AnonymousResourceCollection
    {
        $barangays = $action->execute($request->query('municipality_id'));
        return BarangayResource::collection($barangays);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\StreetResource;
use App\Http\Resources\Api\TpResource;
use App\Models\City;
use App\Models\Region;
use Illuminate\Http\Request;

class CityDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Region $region, City $city)
    {
        if ($city->region_id !== $region->id) {
            abort(403, 'Населенний пункт з іншого регіону');
        }

        $streets = $city->streets()->with('streetType')->get();
        $tps = $city->tps()->with('type')->get();
        foreach ($streets as &$street) {
            $street['name'] = $street->fullName();
        }
        foreach ($tps as &$tp) {
            $tp['name'] = $tp->fullName();
        }
        return response()->json([
            'streets' => StreetResource::collection($streets),
            'tps' => TpResource::collection($tps),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

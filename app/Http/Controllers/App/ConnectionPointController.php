<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\ConnectingPoint;
use App\Models\CustomerType;
use App\Models\PowerLineType;
use App\Models\Region;
use App\Models\RoleRegionUser;
use App\Models\Street;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConnectionPointController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Region $region)
    {
        $role = $region->userRole()->name;
        $points =[];
        if ($region->userHasPermission(Auth::user())) {
            $points = ConnectingPoint::all()->where('region_id', $region->id);
        }
        return view('app.index', compact('points', 'region', 'role'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Region $region)
    {
        $customerTypes = CustomerType::all();
        $powerLineTypes = PowerLineType::all();
        $cities = City::citiesInRegion($region);
//        $streets = Street::streetsInCitie($city);
        return view('app.connectionpoints.create', compact('region', 'customerTypes', 'powerLineTypes', 'cities'));
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd($request);
        return view('app.connectionpoints.show', compact('region', 'id'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Region $region, ConnectingPoint $id)
    {
        return view('app.connectionpoints.show', compact('region', 'id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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

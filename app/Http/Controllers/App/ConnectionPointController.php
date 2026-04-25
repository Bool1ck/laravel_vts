<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConnectionPointRequest;
use App\Models\City;
use App\Models\ConnectingPoint;
use App\Models\ConnectingPointWorkType;
use App\Models\CustomerType;
use App\Models\PowerLineType;
use App\Models\Region;
use App\Models\RoleRegionUser;
use App\Models\Street;
use App\Models\Tp;
use App\Models\WorkType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConnectionPointController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Region $region)
    {
          $connectionPoints =[];
        if ($region->userHasPermission(Auth::user())) {
            $connectionPoints = ConnectingPoint::all()->where('region_id', $region->id);
        }
        return view('app.index', compact('connectionPoints', 'region'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Region $region)
    {
        $customerTypes = CustomerType::all();
        $powerLineTypes = PowerLineType::all();
        $cities = $region->Cities;
        $streets = Street::streetsInCity(City::find($cities->toArray()[0]['id']));
        $workTypes = WorkType::all();
        return view('app.connectionpoints.create', compact('region', 'customerTypes', 'powerLineTypes', 'cities', 'streets', 'workTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreConnectionPointRequest $request)
    {
        $validated = $request->validated();
        $city = City::find($validated['city_id']);
        $street = Street::find($validated['street_id']);
        $point_place = $city->cityType->name . ' ' . $city->name .', '.$street->streetType->name . ' ' . $street->name .', буд. ' . $validated['build_number'];
        $tp = Tp::where('name', $validated['tp'])->first();
        $power_point = 'ПЛ-' . $validated['powerLineType'] . 'кВ від ' . $tp->type->name . '-' . $tp->name . ' ' .$tp->city->cityType->name  . $tp->city->name . ', ' . $validated['power_line'] . ' опора №' . $validated['pole'];
        $validated['point_place'] = $point_place;
        $validated['power_point'] = $power_point;
        $workTypes = $validated['workTypes'];
        $region = $validated['region_id'];
        unset($validated['build_number']);
        unset($validated['workTypes']);
        unset($validated['tp']);
        unset($validated['pole']);
        unset($validated['power_line']);
        unset($validated['powerLineType']);
        unset($validated['city_id']);
        unset($validated['street_id']);
        $connectionPoint = ConnectingPoint::create($validated);
        foreach ($workTypes as $workType) {
            ConnectingPointWorkType::create(['worktype_id' => $workType, 'pointid' => $connectionPoint->id]);
        }
        return redirect(route('connection_point.show', ['region' => $region, 'cp' => $connectionPoint->id]));
    }

    /**
     * Display the specified resource.
     */
    public function show(Region $region, ConnectingPoint $cp)
    {
//        $cp = $cp->toArray();
        return view('app.connectionpoints.show', compact('region', 'cp'));
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

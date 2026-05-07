<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConnectionPointRequest;
use App\Http\Requests\UpdateConnectionPointRequest;
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
use Carbon\Carbon;
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
            $connectionPoints = ConnectingPoint::all()->where('region_id', $region->id);
        return view('app.index', compact('connectionPoints', 'region'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Region $region)
    {
        $customerTypes = CustomerType::all();
        $powerLineTypes = PowerLineType::all();
        $cities = $region->cities;
        $workTypes = WorkType::all();
        return view('app.connectionpoints.create', compact('region', 'customerTypes', 'powerLineTypes', 'cities', 'workTypes'));
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
        $workTypes_id = $validated['workTypes'];
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
        foreach ($workTypes_id as $workType_id) {
            ConnectingPointWorkType::create(['worktype_id' => $workType_id, 'pointid' => $connectionPoint->id]);
        }
        return redirect(route('connection_point.show', ['region' => $region, 'cp' => $connectionPoint->id]));
    }

    /**
     * Display the specified resource.
     */
    public function show(Region $region, ConnectingPoint $cp)
    {
        $cpWorkTypes = ConnectingPointWorkType::where('pointid', $cp->id)->get();
        return view('app.connectionpoints.show', compact('region', 'cp', 'cpWorkTypes'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Region $region, ConnectingPoint $cp)
    {
        $cpWorkTypes = ConnectingPointWorkType::where('pointid', $cp->id)->get();
        $workTypes = WorkType::all();
        $customerTypes = CustomerType::all();
        return view('app.connectionpoints.edit', compact('region', 'cp', 'cpWorkTypes', 'workTypes', 'customerTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateConnectionPointRequest $request, Region $region, ConnectingPoint $cp)
    {
        $validated = $request->validated();
        $workTypes_id = $validated['workTypes'];
        if(!is_null($validated['payment_date'])) {
            if ($validated['power']  == 5) {
                $days = 45;
            } elseif ($validated['power']  > 5 && $validated['power']  < 16) {
                $days = 60;
            } elseif ($validated['power']  > 15 && $validated['power']  < 30) {
                $days = 75;
            } elseif ($validated['power']  >= 30) {
                $days = 90;
            }
            $validated['perform_by_date'] = Carbon::parse($validated['payment_date'])->addDays($days)->format('Y-m-d');
        } else {
            $validated['perform_by_date'] = null;
        }
        unset($validated['workTypes']);
            $cp->update($validated);
            $cp->workTypes()->sync($workTypes_id);
        return redirect(route('connection_point.show', ['region' => $region, 'cp' => $cp]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

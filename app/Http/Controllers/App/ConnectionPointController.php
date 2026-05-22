<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConnectionPointRequest;
use App\Http\Requests\UpdateConnectionPointMERequest;
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
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class ConnectionPointController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Region $region, string $completed = '')
    {
        $connectionPoints = [];
        $connectionPoints = ConnectingPoint::where('region_id', $region->id)->when(
            $completed == "completed",
            function ($query) {
                $query->whereNotNull('performance_date');
            },
            function ($query) {
                $query->whereNull('performance_date');
            }
        )->paginate(25);
        return view('app.index', compact('connectionPoints', 'region', 'completed'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Region $region)
    {
        $this->authorize('create', [ConnectingPoint::class, $region]);
        $customerTypes = CustomerType::select('id', 'name')->orderBy('id')->get();
        $powerLineTypes = PowerLineType::select('id', 'name')->orderBy('id')->get();
        $cities = $region->cities()->with('cityType')->get();
        $workTypes = WorkType::select('id', 'name')->orderBy('id')->get();
        return view('app.connectionpoints.create', compact('region', 'customerTypes', 'powerLineTypes', 'cities', 'workTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreConnectionPointRequest $request, Region $region)
    {
        $this->authorize('create', [ConnectingPoint::class, $region]);
        $validated = $request->validated();
        $city = City::find($validated['city_id']);
        $street = Street::find($validated['street_id']);
        $point_place = $city->fullName() . ', ' . $street->fullName() . ', буд. ' . $validated['build_number'];
        $tp = Tp::find($validated['tp_id']);
        $power_point = 'ПЛ-' . $validated['powerLineType'] . 'кВ від ' . $tp->fullName() . ', ' . $tp->city->fullName() . ', ' . $validated['power_line'] . ' опора №' . $validated['pole'];
        $validated['point_place'] = $point_place;
        $validated['power_point'] = $power_point;
        $workTypes_id = $validated['workTypes'];
        $region = $validated['region_id'];
        unset($validated['build_number']);
        unset($validated['workTypes']);
        unset($validated['tp_id']);
        unset($validated['pole']);
        unset($validated['power_line']);
        unset($validated['powerLineType']);
        unset($validated['city_id']);
        unset($validated['street_id']);
        $connectionPoint = ConnectingPoint::create($validated);
        foreach ($workTypes_id as $workType_id) {
            ConnectingPointWorkType::create(['worktype_id' => $workType_id, 'pointid' => $connectionPoint->id]);
        }
        return redirect(route('connection_point.show', ['region' => $region, 'cp' => $connectionPoint]));
    }

    /**
     * Display the specified resource.
     */
    public function show(Region $region, ConnectingPoint $cp)
    {
        $cpWorkTypes = ConnectingPointWorkType::with('workType')->where('pointid', $cp->id)->get();
        return view('app.connectionpoints.show', compact('region', 'cp', 'cpWorkTypes'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Region $region, ConnectingPoint $cp)
    {
        $this->authorize('update', $cp);
        $cpWorkTypes = ConnectingPointWorkType::with('workType')->where('pointid', $cp->id)->get();
        $workTypes = WorkType::select('id', 'name')->orderBy('id')->get();
        $customerTypes = CustomerType::select('id', 'name')->orderBy('id')->get();
        return view('app.connectionpoints.edit', compact('region', 'cp', 'cpWorkTypes', 'workTypes', 'customerTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Region $region, ConnectingPoint $cp)
    {
        $this->authorize('update', $cp);
        if (!$cp->performance_date) {
            if (Auth::user()->isMainEngineerInRegion($region)) {
                $data = app(UpdateConnectionPointMERequest::class)->validated();
                $this->updateForMainEnginier($data, $region, $cp);
            } elseif (Auth::user()->isCanEditRegion($region)) {
                $data = app(UpdateConnectionPointRequest::class)->validated();
                $this->updateFull($data, $region, $cp);
            }
            return redirect(route('connection_point.show', ['region' => $region, 'cp' => $cp]));
        }
        abort(403, 'У вас недостаточно прав для редактирования этой точки.');
    }

    private function updateFull(array $validated, Region $region, ConnectingPoint $cp)
    {
        $this->authorize('update', $cp);
        if (!is_null($validated['payment_date'])) {
            $power = $validated['power'];
            $days = match (true) {
                $power <= 5 => 45,
                $power < 16 => 60,
                $power < 30 => 75,
                default => 90,
            };
            $validated['perform_by_date'] = Carbon::parse($validated['payment_date'])->addDays($days)->format('Y-m-d');
        } else {
            $validated['perform_by_date'] = null;
        }
        $cp->update(Arr::except($validated, ['workTypes']));
        $cp->workTypes()->sync($validated['workTypes'] ?? []);
        return redirect(route('connection_point.show', ['region' => $region, 'cp' => $cp]));
    }

    private function updateForMainEnginier(array $validated, Region $region, ConnectingPoint $cp)
    {
        $this->authorize('update', $cp);
        $cp->update($validated);
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

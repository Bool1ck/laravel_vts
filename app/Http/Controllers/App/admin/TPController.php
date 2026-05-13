<?php

namespace App\Http\Controllers\App\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTpRequest;
use App\Http\Requests\Admin\UpdateTpRequest;
use App\Models\City;
use App\Models\Region;
use App\Models\Tp;
use App\Models\TpType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TPController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Region $region)
    {

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Region $region, City $city = null)
    {
        $this->authorize('create', [Tp::class, $region]);
        $TpTypes = TpType::all();
        return view('app.admin.tps.create', compact('region', 'TpTypes', 'city'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTpRequest $request, Region $region)
    {
        $this->authorize('create', [Tp::class, $region]);
        $validated = $request->validated();
        if ($region->isHasTpNumber($validated['name'])) {
            return back()->withErrors(['custom_field' => 'ТП з таким номером вже існує!'])->withInput();
        }
        $tp = Tp::create($validated);
        $city = $tp->city;
        return redirect(route('admin.tps.show',['region' => $region, 'city' => $city]));
    }

    /**
     * Display the specified resource.
     */
    public function show(Region $region, City $city)
    {
        $this->authorize('view', [Tp::class, $region, $city]);
        $tps = $city->tps()->paginate(20);
        return view('app.admin.tps.show', compact('region', 'city', 'tps'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Region $region, Tp $tp)
    {
        $this->authorize('update', [Tp::class, $region, $tp]);
        $TpTypes = TpType::all();
        $cities = $region->cities();
        return view('app.admin.tps.edit', compact('region', 'tp', 'TpTypes', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTpRequest $request, Region $region, Tp $tp)
    {
        $this->authorize('update', [Tp::class, $region, $tp]);
        $validated = $request->validated();
        $tp->update($validated);
        $city = $validated['city_id'];
        return redirect(route('admin.tps.show',['region' => $region, 'city' => $city]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Region $region, Tp $tp)
    {
        $this->authorize('delete', [Tp::class, $region, $tp]);
        $city = $tp->city;
        $tp->delete();
        return redirect(route('admin.tps.show',['region' => $region, 'city' => $city]));
    }
}

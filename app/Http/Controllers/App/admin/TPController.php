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
        $cities = $region->cities()->paginate(1);
        return view('app.admin.tps.index', compact('region', 'cities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Region $region)
    {
        $TpTypes = TpType::all();
        $cities = $region->cities();
        return view('app.admin.tps.create', compact('region', 'TpTypes', 'cities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTpRequest $request, Region $region)
    {
        $validated = $request->validated();
        if ($region->isHasTpNumber($validated['name'])) {
            return back()->withErrors(['custom_field' => 'ТП з таким номером вже існує!'])->withInput();
        }
        $tp = Tp::create($validated);
        return redirect(route('admin.tps.index',['region' => $region]));
    }

    /**
     * Display the specified resource.
     */
    public function show(Region $region, City $city)
    {
        return view('app.admin.tps.show', compact('region', 'city'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Region $region, Tp $tp)
    {
        $TpTypes = TpType::all();
        $cities = $region->cities();
        return view('app.admin.tps.edit', compact('region', 'tp', 'TpTypes', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTpRequest $request, Region $region, Tp $tp)
    {
        $validated = $request->validated();
        $tp->update($validated);
        return redirect(route('admin.tps.index',['region' => $region]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Region $region, Tp $tp)
    {
        $tp->delete();
        return redirect(route('admin.tps.index',['region' => $region]));
    }
}

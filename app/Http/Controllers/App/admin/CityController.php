<?php

namespace App\Http\Controllers\App\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCityRequest;
use App\Http\Requests\Admin\UpdateCityRequest;
use App\Models\City;
use App\Models\CityType;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Region $region)
    {
        $cities = $region->cities()->paginate(5);
        return view('app.admin.cities.index', compact('region', 'cities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Region $region)
    {
        $cityTypes = CityType::all();
        return view('app.admin.cities.create', compact('region', 'cityTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCityRequest $request , Region $region)
    {
        $validated = $request->validated();
        $request->validate([
            'name' => [
                'required', 'string',
                Rule::unique('cities')->where(fn ($query) => $query->where('city_type_id', $request->city_type_id))
            ],
        ]);
        $validated['region_id'] = $region->id;
        $city = City::create($validated);
        return redirect(route('admin.cities.index', compact('region')));
    }

    /**
     * Display the specified resource.
     */
    public function show(Region $region, City $city)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Region $region, City $city)
    {
        $cityTypes = CityType::all();
        return view('app.admin.cities.edit', compact('region', 'city', 'cityTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCityRequest $request, Region $region, City $city)
    {
        $this->authorize('update', $city);
        $validated = $request->validated();
        $request->validate([
            'name' => [
                'required', 'string',
                Rule::unique('cities')->where(fn ($query) => $query->where('city_type_id', $request->city_type_id))
            ],
        ]);
        $validated['region_id'] = $region->id;
        $city->update($validated);
        return redirect(route('admin.cities.index', compact('region')));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Region $region, City $city)
    {
        $this->authorize('delete', $city);
        $city->delete();
        return redirect(route('admin.cities.index', compact('region')));
    }
}

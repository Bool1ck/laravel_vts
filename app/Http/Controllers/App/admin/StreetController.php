<?php

namespace App\Http\Controllers\App\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStreetRequest;
use App\Http\Requests\Admin\UpdateStreetRequest;
use App\Models\City;
use App\Models\Region;
use App\Models\Street;
use App\Models\StreetType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StreetController extends Controller
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
        $this->authorize('create', [Street::class, $region]);
        $streetTypes = StreetType::all();
        return view('app.admin.streets.create', compact('region', 'streetTypes', 'city'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStreetRequest $request, Region $region)
    {
        $this->authorize('create', [Street::class, $region]);
        $validated = $request->validated();
        $request->validate([
            'name' => [
                'required', 'string',
                Rule::unique('streets')->where(fn($query) => $query->where('street_type_id',
                    $request->street_type_id)->where('city_id', $request->city_id))
            ],
        ]);
        $street = Street::create($validated);
        $city = $street->city;
        return redirect(route('admin.streets.show', compact('region', 'city')));
    }

    /**
     * Display the specified resource.
     */
    public function show(Region $region, City $city)
    {
        $this->authorize('view', [Street::class, $region, $city]);
        return view('app.admin.streets.show', compact('region', 'city'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Region $region, Street $street)
    {
        $this->authorize('update', [Street::class, $region, $street]);
        $cities = $region->cities();
        $streetTypes = StreetType::all();
        return view('app.admin.streets.edit', compact('region', 'cities', 'streetTypes', 'street'));
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStreetRequest $request, Region $region, Street $street)
    {
        $this->authorize('update', [Street::class, $region, $street]);
        $validated = $request->validated();
        $request->validate([
            'name' => [
                'required', 'string',
                Rule::unique('streets')->where(fn($query) => $query->where('street_type_id',
                    $request->street_type_id)->where('city_id', $request->city_id))
            ],
        ]);
        $street->update($validated);
        $city = $validated['city_id'];
        return redirect(route('admin.streets.show', compact('region', 'city')));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Region $region, Street $street)
    {
        $this->authorize('delete', [Street::class, $region, $street]);
        $city = $street->city;
        $street->delete();
        return redirect(route('admin.streets.show', compact('region', 'city')));
    }
}

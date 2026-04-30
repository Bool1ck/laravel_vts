<?php

namespace App\Http\Controllers\App\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStreetRequest;
use App\Http\Requests\Admin\UpdateStreetRequest;
use App\Models\Region;
use App\Models\Street;
use App\Models\StreetType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StreetsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Region $region)
    {
        $cities = $region->cities()->paginate(1);
        return view('app.admin.streets.index', compact('region', 'cities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Region $region)
    {
        $streetTypes = StreetType::all();
        return view('app.admin.streets.create', compact('region', 'streetTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStreetRequest $request, Region $region)
    {
        $validated = $request->validated();
        $request->validate([
            'name' => [
                'required', 'string',
                Rule::unique('streets')->where(fn($query) => $query->where('street_type_id',
                    $request->street_type_id)->where('city_id', $request->city_id))
            ],
        ]);
        $street = Street::create($validated);
        return redirect(route('admin.streets.index', compact('region')));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Region $region, Street $street)
    {
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
        $validated = $request->validated();
        $request->validate([
            'name' => [
                'required', 'string',
                Rule::unique('streets')->where(fn($query) => $query->where('street_type_id',
                    $request->street_type_id)->where('city_id', $request->city_id))
            ],
        ]);
        $street->update($validated);
        return redirect(route('admin.streets.index', compact('region')));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Region $region, Street $street)
    {
        $street->delete();
        return redirect(route('admin.streets.index', compact('region')));
    }
}

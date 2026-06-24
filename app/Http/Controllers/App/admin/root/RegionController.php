<?php

declare(strict_types=1);

namespace App\Http\Controllers\App\admin\root;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\root\StoreRegionRequest;
use App\Http\Requests\Admin\root\UpdateRegionRequest;
use App\Models\City;
use App\Models\Region;
use App\Services\Admin\CityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $regions = Region::all();

        return view('app.admin.root.regions.index', compact('regions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('app.admin.root.regions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRegionRequest $request): RedirectResponse
    {
        $data = $request->validated();
        Region::create($data);

        // 3. HTTP-ответ
        return to_route('root.regions.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Region $region, City $city) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Region $region): View
    {
        // 3. HTTP-ответ
        return view('app.admin.root.regions.edit', compact('region'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRegionRequest $request, Region $region): RedirectResponse
    {
        $data = $request->validated();
        $region->update($data);

        // 3. HTTP-ответ
        return to_route('root.regions.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    //    public function destroy(Region $region, City $city): RedirectResponse
    //    {
    //        // 1. Проверка прав (HTTP-слой)
    //        //        $this->authorize('delete', $city);
    //        // 2. Делегирование бизнес-логики сервису
    //        $this->cityService->delete($city);
    //
    //        // 3. HTTP-ответ
    //        return to_route('admin.cities.index', compact('region'));
    //    }
}

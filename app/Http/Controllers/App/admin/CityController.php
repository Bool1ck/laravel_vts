<?php

declare(strict_types=1);

namespace App\Http\Controllers\App\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCityRequest;
use App\Http\Requests\Admin\UpdateCityRequest;
use App\Models\City;
use App\Models\CityType;
use App\Models\Region;
use App\Services\Admin\CityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CityController extends Controller
{
    public function __construct(
        protected CityService $cityService,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Region $region): View
    {
        // 1. список міст
        $cities = $region->cities()->with('cityType')->paginate(20);

        // 2. HTTP-відповідь
        return view('app.admin.cities.index', compact('region', 'cities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Region $region): View
    {
        // 1. список типов міст
        $cityTypes = CityType::select('id', 'name')->orderBy('id')->get();

        // 2. HTTP-відповідь
        return view('app.admin.cities.create', compact('region', 'cityTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCityRequest $request, Region $region): RedirectResponse
    {
        // 1. Обробка даних сервісом
        $this->cityService->create($region, $request->validated());

        // 2. HTTP-редірект
        return to_route('admin.cities.index', compact('region'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Region $region, City $city) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Region $region, City $city): View
    {
        // 1. список типов міст
        $cityTypes = CityType::select('id', 'name')->orderBy('id')->get();

        // 2. HTTP-відповідь
        return view('app.admin.cities.edit', compact('region', 'city', 'cityTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCityRequest $request, Region $region, City $city): RedirectResponse
    {
        // 1. Обробка даних сервісом
        $this->cityService->update($city, $request->validated());

        // 2. HTTP-редірект
        return to_route('admin.cities.index', compact('region'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Region $region, City $city): RedirectResponse
    {
        // 1. Обробка даних сервісом
        $this->cityService->delete($city);

        // 3. HTTP-редірект
        return to_route('admin.cities.index', compact('region'));
    }
}

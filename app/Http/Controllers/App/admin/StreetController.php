<?php

namespace App\Http\Controllers\App\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStreetRequest;
use App\Http\Requests\Admin\UpdateStreetRequest;
use App\Models\City;
use App\Models\Region;
use App\Models\Street;
use App\Models\StreetType;
use App\Services\Admin\StreetService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StreetController extends Controller
{
    public function __construct(
        protected StreetService $streetService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Region $region)
    {
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Region $region, City $city = null) : View
    {
        // 1. Проверка прав (HTTP-слой)
        $this->authorize('create', [Street::class, $region]);

        // 2. список типов улиц
        $streetTypes = StreetType::select('id', 'name')->orderBy('id')->get();

        if (!is_null($city)) {
            $city->load('cityType');
        }

        $cities = $region->cities()->with('cityType')->get();

        // 3. HTTP-ответ
        return view('app.admin.streets.create', compact('region', 'streetTypes', 'cities', 'city'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStreetRequest $request, Region $region) : RedirectResponse
    {
        // 1. Проверка прав (HTTP-слой)
        $this->authorize('create', [Street::class, $region]);

        // 2. Делегирование бизнес-логики сервису
        $street = $this->streetService->create($request->validated());

        $city = $street->city;
        // 3. HTTP-ответ
        return to_route('admin.streets.show', compact('region', 'city'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Region $region, City $city) : View
    {
        // 1. Проверка прав (HTTP-слой)
        $this->authorize('view', [Street::class, $region, $city]);
        // 2. Streets list with streetType
        $streets = $city->streets()->with('streetType')->paginate(20);
        // 3. HTTP-ответ
        return view('app.admin.streets.show', compact('region', 'city', 'streets'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Region $region, Street $street) : View
    {
        // 1. Проверка прав (HTTP-слой)
        $this->authorize('update', [Street::class, $region, $street]);

        //2. cities and streetTypes lists
        $cities = $region->cities()->with('cityType')->orderBy('id')->get();
        $streetTypes = StreetType::select('id', 'name')->orderBy('id')->get();

        // 3. HTTP-ответ
        return view('app.admin.streets.edit', compact('region', 'cities', 'streetTypes', 'street'));
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStreetRequest $request, Region $region, Street $street) : RedirectResponse
    {
        // 1. Проверка прав (HTTP-слой)
        $this->authorize('update', [Street::class, $region, $street]);

        // 2. Делегирование бизнес-логики сервису
        $street = $this->streetService->update($street, $request->validated());

        // 2.1. City of Street
        $city = $street->city;

        // 3. HTTP-ответ
        return to_route('admin.streets.show', compact('region', 'city'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Region $region, Street $street) : RedirectResponse
    {
        // 1. Проверка прав (HTTP-слой)
        $this->authorize('delete', [Street::class, $region, $street]);

        // 2. City of Street
        $city = $street->city;

        // 2.1 Делегирование бизнес-логики сервису
        $this->streetService->delete($street);

        // 3. HTTP-ответ
        return to_route('admin.streets.show', compact('region', 'city'));
    }
}

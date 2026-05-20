<?php

namespace App\Http\Controllers\App\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTpRequest;
use App\Http\Requests\Admin\UpdateTpRequest;
use App\Models\City;
use App\Models\Region;
use App\Models\Tp;
use App\Models\TpType;
use App\Services\Admin\TPService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TPController extends Controller
{
    // Внедряем сервис через конструктор
    public function __construct(
        protected TPService $tpService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Region $region)
    {
        // Метод пустой, так как список выводится в разрезе городов в методе show
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Region $region, City $city = null) : View
    {
        // 1. Проверка прав (HTTP-слой)
        $this->authorize('create', [Tp::class, $region]);

        // 2. Список типов TP
        $TpTypes = TpType::select('id', 'name')->orderBy('id')->get();

        // 3. HTTP-ответ
        return view('app.admin.tps.create', compact('region', 'TpTypes', 'city'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTpRequest $request, Region $region) : RedirectResponse
    {
        // 1. Проверка прав (HTTP-слой)
        $this->authorize('create', [Tp::class, $region]);

        // 3. Делегирование бизнес-логики сервису
        $tp = $this->tpService->create($request->validated());

        $city = $tp->city;

        // 4. HTTP-ответ
        return to_route('admin.tps.show', compact('region', 'city'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Region $region, City $city) : View
    {
        // 1. Проверка прав (HTTP-слой)
        $this->authorize('view', [Tp::class, $region, $city]);

        $tps = $city->tps()->paginate(20);

        // 3. HTTP-ответ
        return view('app.admin.tps.show', compact('region', 'city', 'tps'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Region $region, Tp $tp) : View
    {
        // 1. Проверка прав (HTTP-слой)
        $this->authorize('update', [Tp::class, $region, $tp]);

        $TpTypes = TpType::select('id', 'name')->orderBy('id')->get();

        // 2. ИСПРАВЛЕНО: Вызываем метод get() вместо сырого Relation объекта
        $cities = $region->cities()->select('id', 'name')->get();

        // 3. HTTP-ответ
        return view('app.admin.tps.edit', compact('region', 'tp', 'TpTypes', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTpRequest $request, Region $region, Tp $tp) : RedirectResponse
    {
        // 1. Проверка прав (HTTP-слой)
        $this->authorize('update', [Tp::class, $region, $tp]);

        // 2. Обновление через сервис
        $updatedTp = $this->tpService->update($tp, $request->validated());

        $city = $updatedTp->city;

        // 3. HTTP-ответ
        return to_route('admin.tps.show', compact('region', 'city'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Region $region, Tp $tp) : RedirectResponse
    {
        // 1. Проверка прав (HTTP-слой)
        $this->authorize('delete', [Tp::class, $region, $tp]);

        $city = $tp->city;

        // 2. Удаление через сервис
        $this->tpService->delete($tp);

        // 3. HTTP-ответ
        return to_route('admin.tps.show', compact('region', 'city'));
    }
}

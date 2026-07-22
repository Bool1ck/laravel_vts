<?php

declare(strict_types=1);

namespace App\Http\Controllers\App\admin\root;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\root\StoreRegionRequest;
use App\Http\Requests\Admin\root\UpdateRegionRequest;
use App\Models\City;
use App\Models\Region;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;

class RegionController extends Controller
{
    /**
     * Ручне скидання та відновлення стану тестової пісочниці суперадміном
     */
    public function resetSandbox(): RedirectResponse
    {
        // 1. перевірка на Головного Суперадміністратора
        if (! auth()->user()->isSuperAdmin()) {
            abort(403, 'Ця дія доступна лише Головному Суперадміністратору.');
        }

        // 2. Якщо додаток запущено НЕ в режимі staging (наприклад, на Production) —
        // жорстко блокуємо виконання команди, захищаючи живі дані клієнтів!
        if (config('app.env') !== 'sandbox') {
            abort(403, 'Помилка безпеки: скидання пісочниці дозволено лише в середовищі staging.');
        }

        Artisan::call('db:seed', [
            '--class' => 'DemoSandboxSeeder',
        ]);

        return to_route('root.regions.index')
            ->with('success', 'Стан тестової пісочниці "Тестовий РЕМ" успішно відновлено до початкового рівня!');
    }

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

        // 3. HTTP-редірект
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
        // 3. HTTP-відповідь
        return view('app.admin.root.regions.edit', compact('region'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRegionRequest $request, Region $region): RedirectResponse
    {
        $data = $request->validated();
        $region->update($data);

        // 3. HTTP-редірект
        return to_route('root.regions.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Region $region): RedirectResponse
    {
        $region->delete();

        // 3. HTTP-редірект
        return to_route('root.regions.index')
            ->with('success', 'Регіон (РЕМ) успішно перенесено до архіву (м\'яке видалення).');
    }
}

<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConnectionPointRequest;
use App\Http\Requests\UpdateConnectionPointMERequest;
use App\Http\Requests\UpdateConnectionPointRequest;
use App\Models\City;
use App\Models\ConnectingPoint;
use App\Models\ConnectingPointWorkType;
use App\Models\CustomerType;
use App\Models\PowerLineType;
use App\Models\Region;
use App\Models\Street;
use App\Models\Tp;
use App\Models\WorkType;
use App\Services\App\ConnectionPointService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ConnectionPointController extends Controller

{
    public function __construct(
        protected ConnectionPointService $connectionPointService
    )
    {
    }

    /**
     * Display a listing of the resource.
     * $completed ключ из роута для фильтрации вывода завершенных точек
     */
    public function index(Region $region, string $completed = ''): View
    {
        $this->authorize('viewAny', [ConnectingPoint::class, $region]);
        // 1. Делегирование бизнес-логики сервису
        $connectionPoints = $this->connectionPointService->index($region, $completed);
        // 2. HTTP-ответ
        return view('app.index', compact('connectionPoints', 'region', 'completed'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Region $region): View
    {
        // 1. Проверка прав (HTTP-слой)
        $this->authorize('create', [ConnectingPoint::class, $region]);

        // 2. выборка данных для страницы
        $customerTypes = CustomerType::select('id', 'name')->orderBy('id')->get();
        $powerLineTypes = PowerLineType::select('id', 'name')->orderBy('id')->get();
        $cities = $region->cities()->with('cityType')->get();
        $workTypes = WorkType::select('id', 'name')->orderBy('id')->get();

        // 3. HTTP-ответ
        return view('app.connectionpoints.create', compact('region', 'customerTypes', 'powerLineTypes', 'cities', 'workTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreConnectionPointRequest $request, Region $region): RedirectResponse
    {
        // 1. Проверка прав (HTTP-слой)
        $this->authorize('create', [ConnectingPoint::class, $region]);

        // 2. Делегирование бизнес-логики сервису
        $connectionPoint = $this->connectionPointService->create($region, $request->validated());

        // 3. HTTP-ответ
        return to_route('connection_point.show', ['region' => $region, 'cp' => $connectionPoint]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Region $region, ConnectingPoint $cp): View
    {
        // 1. Жадная подгрузка данных
        $cp->load('workTypes');
        // 3. HTTP-ответ
        return view('app.connectionpoints.show', compact('region', 'cp'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Region $region, ConnectingPoint $cp): View
    {
        // 1. Проверка прав (HTTP-слой)
        $this->authorize('update', $cp);

        // 2. выборка данных для страницы
        $cp->load('workTypes');
        $workTypes = WorkType::select('id', 'name')->orderBy('id')->get();
        $customerTypes = CustomerType::select('id', 'name')->orderBy('id')->get();

        // 3. HTTP-ответ
        return view('app.connectionpoints.edit', compact('region', 'cp', 'workTypes', 'customerTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Region $region, ConnectingPoint $cp): RedirectResponse
    {
        $this->authorize('update', $cp);

        // 1. Проверяем бизнес-правило закрытия точки
        if ($cp->performance_date) {
            abort(404);
        }

        // 2. Определяем контекст пользователя и получаем валидированные данные
        $data = match (true) {
            auth()->user()->isMainEngineerInRegion($region) => app(UpdateConnectionPointMERequest::class)->validated(),
            auth()->user()->isCanEditRegion($region) => app(UpdateConnectionPointRequest::class)->validated(),
            default => abort(403)
        };

        // 3. Передаем в сервис чистый массив данных и флаг роли
        $isMainEngineer = auth()->user()->isMainEngineerInRegion($region);
        $this->connectionPointService->update($cp, $data, $isMainEngineer);

        return to_route('connection_point.show', ['region' => $region, 'cp' => $cp]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

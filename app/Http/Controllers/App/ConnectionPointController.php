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
        protected ConnectionPointService $connetctionPointService
    ) {}
    /**
     * Display a listing of the resource.
     * $completed ключ из роута для фильтрации вывода завершенных точек
     */
    public function index(Region $region, string $completed = '') : View
    {
        // 1. Делегирование бизнес-логики сервису
        $connectionPoints = $this->connetctionPointService->index($region, $completed);
        // 2. HTTP-ответ
        return view('app.index', compact('connectionPoints', 'region', 'completed'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Region $region) : View
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
        $this->connetctionPointService->create($region, $request->validated());

        // 3. HTTP-ответ
        return to_route('connection_point.show', ['region' => $region, 'cp' => $connectionPoint]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Region $region, ConnectingPoint $cp) : View
    {
        // 1. выборка данных для страницы
        $cpWorkTypes = ConnectingPointWorkType::with('workType')->where('pointid', $cp->id)->get();
        // 3. HTTP-ответ
        return view('app.connectionpoints.show', compact('region', 'cp', 'cpWorkTypes'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Region $region, ConnectingPoint $cp) : View
    {
        // 1. Проверка прав (HTTP-слой)
        $this->authorize('update', $cp);

        // 2. выборка данных для страницы
        $cpWorkTypes = ConnectingPointWorkType::with('workType')->where('pointid', $cp->id)->get();
        $workTypes = WorkType::select('id', 'name')->orderBy('id')->get();
        $customerTypes = CustomerType::select('id', 'name')->orderBy('id')->get();

        // 3. HTTP-ответ
        return view('app.connectionpoints.edit', compact('region', 'cp', 'cpWorkTypes', 'workTypes', 'customerTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Region $region, ConnectingPoint $cp): RedirectResponse
    {
        // 1. Проверка прав (HTTP-слой)
        $this->authorize('update', $cp);

        if (!$cp->performance_date) {
            if (Auth::user()->isMainEngineerInRegion($region)) {
                $data = app(UpdateConnectionPointMERequest::class)->validated();
                $this->updateForMainEnginier($data, $region, $cp);
            } elseif (Auth::user()->isCanEditRegion($region)) {
                $data = app(UpdateConnectionPointRequest::class)->validated();
                $this->updateFull($data, $region, $cp);
            }
//            return to_route('connection_point.show', ['region' => $region, 'cp' => $cp]);
        }
        abort(403, 'У вас недостаточно прав для редактирования этой точки.');
    }

    private function updateFull(array $validated, Region $region, ConnectingPoint $cp): RedirectResponse
    {
        // 1. Проверка прав (HTTP-слой)
        $this->authorize('update', $cp);
        if (!is_null($validated['payment_date'])) {
            $power = $validated['power'];
            $days = match (true) {
                $power <= 5 => 45,
                $power < 16 => 60,
                $power < 30 => 75,
                default => 90,
            };
            $validated['perform_by_date'] = Carbon::parse($validated['payment_date'])->addDays($days)->format('Y-m-d');
        } else {
            $validated['perform_by_date'] = null;
        }
        $cp->update(Arr::except($validated, ['workTypes']));
        $cp->workTypes()->sync($validated['workTypes'] ?? []);
        return to_route('connection_point.show', ['region' => $region, 'cp' => $cp]);
    }

    private function updateForMainEnginier(array $validated, Region $region, ConnectingPoint $cp): RedirectResponse
    {
        // 1. Проверка прав (HTTP-слой)
        $this->authorize('update', $cp);
        $cp->update($validated);
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

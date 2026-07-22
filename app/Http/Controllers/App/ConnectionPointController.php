<?php

declare(strict_types=1);

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConnectionPointRequest;
use App\Http\Requests\UpdateConnectionPointMERequest;
use App\Http\Requests\UpdateConnectionPointRequest;
use App\Models\ConnectingPoint;
use App\Models\CustomerType;
use App\Models\PowerLineType;
use App\Models\Region;
use App\Models\WorkType;
use App\Services\App\ConnectionPointService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ConnectionPointController extends Controller
{
    public function __construct(
        protected ConnectionPointService $connectionPointService,
    ) {}

    /**
     * Display a listing of the resource.
     * $completed ключ из роута для фильтрации вывода завершенных точек
     */
    public function index(Request $request, Region $region, ?string $filter = null): View
    {
        $filter = $filter ?? 'all_active';
        $start_date = $request->query('start_date', '');
        $end_date = $request->query('end_date', '');
        $connectionPoints = $this->connectionPointService->index($region, $filter, $start_date, $end_date);

        return view('app.index', compact('connectionPoints', 'region'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Region $region): View
    {

        // 1. отримання даних для сторінки
        $customerTypes = CustomerType::select('id', 'name')->orderBy('id')->get();
        $powerLineTypes = PowerLineType::select('id', 'name')->orderBy('id')->get();
        $cities = $region->cities()->with('cityType')->get();
        $workTypes = WorkType::select('id', 'name')->orderBy('id')->get();

        // 2. HTTP-відповідь
        return view('app.connectionpoints.create', compact('region', 'customerTypes', 'powerLineTypes', 'cities', 'workTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreConnectionPointRequest $request, Region $region): RedirectResponse
    {

        // 1. обробка бізнес-логіки сервісом
        $connectionPoint = $this->connectionPointService->create($region, $request->validated());

        // 2. HTTP-відповідь
        return to_route('connection_point.show', ['region' => $region, 'cp' => $connectionPoint]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Region $region, ConnectingPoint $cp): View
    {
        // 1. Жадібне підвантаження даних
        $cp->load('workTypes');

        // 2. HTTP-відповідь
        return view('app.connectionpoints.show', compact('region', 'cp'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Region $region, ConnectingPoint $cp): View
    {

        // 1. отримання даних для сторінки
        $cp->load('workTypes');
        $workTypes = WorkType::select('id', 'name')->orderBy('id')->get();
        $customerTypes = CustomerType::select('id', 'name')->orderBy('id')->get();

        // 2. HTTP-відповідь
        return view('app.connectionpoints.edit', compact('region', 'cp', 'workTypes', 'customerTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Region $region, ConnectingPoint $cp): RedirectResponse
    {

        // 1. Перевірки бізнес-логіки закриття точки
        if ($cp->performance_date && ! Auth::user()->isSuperAdmin()) {
            abort(404);
        }

        // 2. Валідація даних в залежності від типу користувача
        $data = match (true) {
            auth()->user()->isSuperAdmin() => app(UpdateConnectionPointRequest::class)->validated(),
            auth()->user()->isMainEngineerInRegion($region) => app(UpdateConnectionPointMERequest::class)->validated(),
            auth()->user()->isCanEditRegion($region) => app(UpdateConnectionPointRequest::class)->validated(),
            default => abort(403)
        };

        // 3. Передача в сервіс даних та перевірку прав користувача
        //      переробити!!!!
        $isMainEngineer = auth()->user()->isMainEngineerInRegion($region);
        $isSuperAdmin = auth()->user()->isSuperAdmin();
        $this->connectionPointService->update($cp, $data, $isMainEngineer, $isSuperAdmin);

        // 4. HTTP-редірект
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

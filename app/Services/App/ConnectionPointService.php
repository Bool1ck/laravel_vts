<?php

declare(strict_types=1);

namespace App\Services\App;

use App\Models\City;
use App\Models\ConnectingPoint;
use App\Models\Region;
use App\Models\Street;
use App\Models\Tp;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ConnectionPointService
{
    public function index(Region $region, string $filter, string $start_date, string $end_date): LengthAwarePaginator
    {
        $oneWeekAdd = Carbon::now()->addWeek()->format('Y-m-d'); // Минус 1 неделя от текущего времени
        $now = Carbon::now()->format('Y-m-d');

        return ConnectingPoint::with('customerType', 'workTypes')
            ->where('region_id', $region->id)
            ->when(
                $filter === 'execution_out',
                fn ($query) => $query->whereNull('performance_date')->whereNotNull('perform_by_date')->whereBetween('perform_by_date', [$now, $oneWeekAdd]),
            )
            ->when(
                $filter === 'execution_fail',
                fn ($query) => $query->whereNull('performance_date')->whereNotNull('perform_by_date')->whereDate('perform_by_date', '<', $now),
            )
            ->when(
                $filter === 'ordering_materials_out',
                fn ($query) => $query->whereNull('materials_order_date')->whereNull('performance_date'),
            )
            ->when(
                $filter === 'all_active',
                fn ($query) => $query->whereNull('performance_date'),
            )
            ->when(
                $filter === 'completed',
                fn ($query) => $query->whereNotNull('performance_date'),
            )
            ->when(
                $start_date && $end_date,
                fn ($query) => $query->whereBetween('technical_conditions_date', [$start_date, $end_date]),
            )
            ->paginate(25);
    }

    public function create(Region $region, array $data): ConnectingPoint
    {
        $city = City::find($data['city_id']);
        $street = Street::find($data['street_id']);
        $tp = Tp::find($data['tp_id']);

        // Формируем текстовые поля
        $point_place = $city->fullName() . ', ' . $street->fullName() . ', буд. ' . $data['build_number'];
        $power_point = 'ПЛ-' . $data['powerLineType'] . 'кВ від ' . $tp->fullName() . ', ' . $tp->city->fullName() . ', ' . $data['power_line'] . ' опора №' . $data['pole'];

        $data['point_place'] = $point_place;
        $data['power_point'] = $power_point;
        $data['region_id'] = $region->id; // Привязываем к региону

        $workTypes_id = $data['workTypes'] ?? [];

        // Очищаем массив от полей, которых нет в таблице connecting_points
        $insertData = Arr::except($data, ['build_number', 'workTypes', 'tp_id', 'pole', 'power_line', 'powerLineType', 'city_id', 'street_id']);

        return DB::transaction(function () use ($workTypes_id, $insertData) {
            $connectionPoint = ConnectingPoint::create($insertData);
            $connectionPoint->workTypes()->attach($workTypes_id);

            return $connectionPoint;
        });
    }

    /**
     * Обновить данные точки подключения.
     */
    public function update(ConnectingPoint $cp, array $data, bool $isMainEngineer): ConnectingPoint
    {
        // Если это Главный инженер — обновляем напрямую переданные поля
        if ($isMainEngineer) {
            return $this->updateForMainEngineer($data, $cp);
        }

        // Если это обычный инженер — выполняем полную логику с расчетом даты
        return $this->updateFull($data, $cp);
    }

    private function updateFull(array $validated, ConnectingPoint $cp): ConnectingPoint
    {
        if (! empty($validated['payment_date'])) {
            $power = $validated['power'] ?? 0;
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

        return DB::transaction(function () use ($cp, $validated) {
            $cp->update(Arr::except($validated, ['workTypes']));

            if (isset($validated['workTypes'])) {
                $cp->workTypes()->sync($validated['workTypes']);
            }

            return $cp;
        });
    }

    private function updateForMainEngineer(array $validated, ConnectingPoint $cp): ConnectingPoint
    {
        return DB::transaction(function () use ($cp, $validated) {
            $cp->update($validated);

            return $cp;
        });
    }
}

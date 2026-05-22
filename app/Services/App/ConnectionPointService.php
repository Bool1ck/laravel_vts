<?php

namespace App\Services\App;

use App\Models\City;
use App\Models\ConnectingPoint;
use App\Models\ConnectingPointWorkType;
use App\Models\Region;
use App\Models\Street;
use App\Models\Tp;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ConnectionPointService
{

    public function index(Region $region, string $completed) : LengthAwarePaginator
    {
        $connectionPoints = [];
        $connectionPoints = ConnectingPoint::where('region_id', $region->id)->when(
            $completed == "completed",
            function ($query) {
                $query->whereNotNull('performance_date');
            },
            function ($query) {
                $query->whereNull('performance_date');
            }
        )->paginate(25);
        return $connectionPoints;
    }
    /**
     *
     */
    public function create(Region $region, array $data): City
    {
        $city = City::find($data['city_id']);
        $street = Street::find($data['street_id']);
        $point_place = $city->fullName() . ', ' . $street->fullName() . ', буд. ' . $data['build_number'];
        $tp = Tp::find($data['tp_id']);
        $power_point = 'ПЛ-' . $data['powerLineType'] . 'кВ від ' . $tp->fullName() . ', ' . $tp->city->fullName() . ', ' . $data['power_line'] . ' опора №' . $data['pole'];
        $data['point_place'] = $point_place;
        $data['power_point'] = $power_point;
        $workTypes_id = $data['workTypes'];
        $region = $region->id;
        unset($data['build_number']);
        unset($data['workTypes']);
        unset($data['tp_id']);
        unset($data['pole']);
        unset($data['power_line']);
        unset($data['powerLineType']);
        unset($data['city_id']);
        unset($data['street_id']);

        return DB::transaction(function () use ($workTypes_id, $data) {
            $connectionPoint = ConnectingPoint::create($data);
            foreach ($workTypes_id as $workType_id) {
                ConnectingPointWorkType::create(['worktype_id' => $workType_id, 'pointid' => $connectionPoint->id]);
            }
            return $connectionPoint;
        });
    }

    /**
     * Обновить данные .
     */
    public function update(City $city, array $data): City
    {
        return DB::transaction(function () use ($city, $data) {
            $city->update($data);
            return $city;
        });
    }

    /**
     * Удалить .
     */
    public function delete(City $city): bool
    {
        return DB::transaction(function () use ($city) {
            return $city->delete();
        });
    }

}

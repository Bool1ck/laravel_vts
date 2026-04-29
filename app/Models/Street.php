<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Street extends Model
{
    use HasFactory;

    public static function streetsInCity(City $city) {
        return Street::all()->where('city_id', $city->id);
    }

    public function streetType()
    {
        return $this->belongsTo(StreetType::class, 'street_type_id');
    }
}

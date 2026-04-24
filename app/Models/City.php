<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    //

    public  static function citiesInRegion(Region $region) {
        return City::all()->where('region_id', $region->id);
    }
}

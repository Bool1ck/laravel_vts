<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'tp_type_id', 'city_id'])]
class Tp extends Model
{
    use HasFactory;

    public function type()
    {
        return $this->belongsTo(TpType::class, 'tp_type_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function fullName()
    {
        return $this->type->name . '-' . $this->name;
    }
}

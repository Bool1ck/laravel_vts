<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Street extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function streetType()
    {
        return $this->belongsTo(StreetType::class, 'street_type_id');
    }

    public function fullName()
    {
        return $this->streetType->name . ' ' . $this->name;
    }
}

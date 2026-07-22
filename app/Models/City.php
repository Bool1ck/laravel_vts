<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'city_type_id', 'region_id'])]
class City extends Model
{
    use HasFactory;

    public function cityType(): BelongsTo
    {
        return $this->belongsTo(CityType::class, 'city_type_id');
    }

    public function streets(): HasMany
    {
        return $this->hasMany(Street::class, 'city_id', 'id');
    }

    public function tps(): HasMany
    {
        return $this->hasMany(Tp::class, 'city_id', 'id');
    }

    public function fullName(): string
    {
        return $this->cityType->name . ' ' . $this->name;
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }
}

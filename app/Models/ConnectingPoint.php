<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConnectingPoint extends Model
{
    use HasFactory;

    public function region() {
        return $this->belongsTo(Region::class);
    }

    public function customerType() {
        return $this->belongsTo(CustomerType::class);
    }
}

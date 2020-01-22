<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleBrand extends Model
{
    protected $table = 'vehicle_brands';
    protected $fillableee = ['name'];

    public function setNameAttribute($value) {
        $this->attributes['name'] = mb_strtoupper($value);
    }
}

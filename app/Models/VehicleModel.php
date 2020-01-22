<?php

namespace App\Models;

use App\Models\VehicleBrand;
use Illuminate\Database\Eloquent\Model;

class VehicleModel extends Model
{
    protected $table = 'vehicle_models';
    protected $fillableee = ['name','brand_model','vehicle_brands_id'];

    public function setNameAttribute($value) {
        $this->attributes['name'] = mb_strtoupper($value);
    }

    public function setBrandModelAttribute($value) {
        $this->attributes['brand_model'] = mb_strtoupper($value);
    }

    public function brand()
    {
        return $this->belongsTo(VehicleBrand::class, 'vehicle_brands_id');
    }
}

<?php

namespace App\Models;

use App\Models\LicensePlate;
use App\Models\VehicleModel;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $table = 'vehicles';
    protected $fillableee = ['placa','color','anio','vin','chasis','motor','license_plates_id','vehicle_models_id'];

    public function setPlacaAttribute($value) {
        $this->attributes['placa'] = mb_strtoupper($value);
    }

    public function setColorAttribute($value) {
        $this->attributes['color'] = mb_strtoupper($value);
    }

    public function setVinAttribute($value) {
        $this->attributes['vin'] = mb_strtoupper($value);
    }

    public function setChasisAttribute($value) {
        $this->attributes['chasis'] = mb_strtoupper($value);
    }

    public function plate()
    {
        return $this->belongsTo(LicensePlate::class, 'license_plates_id');
    }

    public function model()
    {
        return $this->belongsTo(VehicleModel::class, 'vehicle_models_id');
    }
}

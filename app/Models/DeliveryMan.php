<?php

namespace App\Models;

use App\Models\Person;
use App\Models\Vehicle;
use App\Models\TypeLicense;
use Illuminate\Database\Eloquent\Model;

class DeliveryMan extends Model
{
    protected $table = 'delivery_mans';
    protected $fillableee = ['people_id','type_license_id','vehicles_id'];

    public function person()
    {
        return $this->belongsTo(Person::class, 'people_id');
    }

    public function license()
    {
        return $this->belongsTo(TypeLicense::class, 'type_license_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicles_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LicensePlate extends Model
{
    protected $table = 'license_plates';
    protected $fillableee = ['name','type'];

    public function setNameAttribute($value) {
        $this->attributes['name'] = mb_strtoupper($value);
    }

    public function setTypeAttribute($value) {
        $this->attributes['type'] = mb_strtoupper($value);
    }
}

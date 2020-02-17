<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeLicense extends Model
{
    protected $table = 'type_license';
    protected $fillableee = ['name','type'];

    public function setNameAttribute($value) {
        $this->attributes['name'] = mb_strtoupper($value);
    }

    public function setTypeAttribute($value) {
        $this->attributes['type'] = mb_strtoupper($value);
    }
}

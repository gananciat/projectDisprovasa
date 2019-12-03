<?php

namespace App\Models;

use App\Models\Municipality;
use Illuminate\Database\Eloquent\Model;

class Departament extends Model
{
    protected $table = 'departaments';
    protected $fillable = ['name'];

    public function setNameAttribute($valor) {
        $this->attributes['name'] = strtoupper($valor);    
    }

    public function municipalities()
    {
        return $this->hasMany(Municipality::class);
    }
}

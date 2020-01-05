<?php

namespace App\Models;

use App\Models\Municipality;
use App\Models\ProviderPhone;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $table = 'provider';
    protected $fillable = ['nit','name','direction','municipalities_id'];

    public function phones()
    {
    	return $this->hasMany(ProviderPhone::class,'provider_id');
    }

    public function municipality()
    {
    	return $this->belongsTo(Municipality::class,'municipalities_id');
    }
}

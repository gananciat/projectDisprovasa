<?php

namespace App\Models;

use App\Models\Provider;
use Illuminate\Database\Eloquent\Model;

class ProviderPhone extends Model
{
    protected $table = 'provider_phone';
    protected $fillable = ['number','provider_id'];

    public function provider()
    {
    	return $this->belongsTo(Provider::class,'provider_id');
    }
}

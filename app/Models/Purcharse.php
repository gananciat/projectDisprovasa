<?php

namespace App\Models;

use App\Models\Provider;
use App\Models\PurcharseDetail;
use Illuminate\Database\Eloquent\Model;

class Purcharse extends Model
{
    protected $table = 'purcharses';
    protected $fillable = ['date','no_prof','total','provider_id'];

    public function details()
    {
        return $this->hasMany(PurcharseDetail::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}

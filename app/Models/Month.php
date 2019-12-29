<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;

class Month extends Model
{
    protected $table = 'months';
    protected $fillable = ['month'];

    public function setMonthAttribute($value) {
        $this->attributes['month'] = mb_strtoupper($value);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

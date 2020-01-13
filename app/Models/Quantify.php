<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quantify extends Model
{
    protected $table = 'quantify';
    protected $fillable = ['sumary_schools','sumary_purchase','subtraction','year','products_id'];
}

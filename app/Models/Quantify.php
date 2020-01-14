<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Quantify extends Model
{
    protected $table = 'quantify';
    protected $fillable = ['sumary_schools','sumary_purchase','subtraction','year','products_id'];

    public function product()
    {
    	return $this->belongsTo(Product::class,'products_id');
    }
}

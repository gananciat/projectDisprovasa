<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductExpiration extends Model
{
    protected $table = 'product_expirations';
    protected $fillable = ['date','quantity','used','return','expiration','current','products_id','purcharse_id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

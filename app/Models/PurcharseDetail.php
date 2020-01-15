<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Purcharse;
use Illuminate\Database\Eloquent\Model;

class PurcharseDetail extends Model
{
    protected $table = 'purcharse_details';
    protected $fillable = ['product_id','purcharse_id','quantity','purcharse_price','decrease'];

    public function purchase()
    {
        return $this->belongsTo(Purcharse::class,'purcharse_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

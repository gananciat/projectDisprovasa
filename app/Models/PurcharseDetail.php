<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Purcharse;
use Illuminate\Database\Eloquent\Model;

class PurcharseDetail extends Model
{
    protected $table = 'purcharse_details';
    protected $fillable = ['product_id','purcharse_id','quantity'];

    public function purcharse()
    {
        return $this->belongsTo(Purcharse::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

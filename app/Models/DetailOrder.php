<?php

namespace App\Models;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProgressOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailOrder extends Model
{
    use SoftDeletes;

    protected $table = 'detail_orders';
    protected $fillable = ['quantity','sale_price','subtotal','observation','on_route','aware',
                           'complete','products_id','orders_id','deliver','refund'];
    protected $dates = ['deleted_at'];

    public function setObservationAttribute($valor) {
        $this->attributes['observation'] = strtoupper($valor);    
    }

    public function product()
    {
        return $this->belongsTo(Product::class,'products_id');
    }

    public function orders()
    {
        return $this->belongsTo(Order::class);
    }

    public function progress()
    {
        return $this->hasOne(ProgressOrder::class,'detail_orders_id');            
    }    
}

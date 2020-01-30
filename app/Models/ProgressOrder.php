<?php

namespace App\Models;

use App\Models\DetailOrder;
use App\Models\OrderStatus;
use App\Models\CheckDeliveryMan;
use Illuminate\Database\Eloquent\Model;

class ProgressOrder extends Model
{
    protected $table = 'progress_orders';
    protected $fillable = ['purchased_amount','order_statuses_id',
    'detail_orders_id','products_id','original_quantity','check'];

    public function detail()
    {
        return $this->belongsTo(DetailOrder::class,'detail_orders_id');            
    } 

    public function order_status()
    {
        return $this->belongsTo(OrderStatus::class,'order_statuses_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class,'products_id');
    }
    
    public function check()
    {
        return $this->hasOne(CheckDeliveryMan::class,'progress_orders_id');
    }
}

<?php

namespace App\Models;

use App\Models\DetailOrder;
use App\Models\OrderStatus;
use Illuminate\Database\Eloquent\Model;

class ProgressOrder extends Model
{
    protected $table = 'progress_orders';
    protected $fillable = ['purchased_amount','order_statuses_id',
    'detail_orders_id','products_id','original_quantity','check'];

    public function details()
    {
        return $this->belongsTo(DetailOrder::class);            
    } 

    public function order_status()
    {
        return $this->belongsTo(OrderStatus::class,'order_statuses_id');
    }
}

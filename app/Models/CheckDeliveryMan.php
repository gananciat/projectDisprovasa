<?php

namespace App\Models;

use App\Models\Order;
use App\Models\Person;
use App\Models\Vehicle;
use App\Models\DeliveryMan;
use App\Models\DetailOrder;
use App\Models\ProgressOrder;
use Illuminate\Database\Eloquent\Model;

class CheckDeliveryMan extends Model
{
    protected $table = 'check_delivery_man';
    protected $fillableee = ['check','orders_id','detail_orders_id','progress_orders_id','delivery_mans_id','vehicles_id','people_id'];

    public function order()
    {
        return $this->belongsTo(Order::class, 'orders_id');
    }

    public function detail()
    {
        return $this->belongsTo(DetailOrder::class, 'detail_orders_id');
    }

    public function progress()
    {
        return $this->belongsTo(ProgressOrder::class, 'progress_orders_id');
    }

    public function delivery()
    {
        return $this->belongsTo(DeliveryMan::class, 'delivery_mans_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicles_id');
    }

    public function person()
    {
        return $this->belongsTo(Person::class, 'people_id');
    }
}

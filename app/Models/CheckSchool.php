<?php

namespace App\Models;

use App\Models\Order;
use App\Models\Person;
use App\Models\School;
use App\Models\DetailOrder;
use App\Models\ProgressOrder;
use Illuminate\Database\Eloquent\Model;

class CheckSchool extends Model
{
    protected $table = 'check_schools';
    protected $fillableee = ['check','orders_id','detail_orders_id','check_delivery_man_id','schools_id','people_id'];

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

    public function school()
    {
        return $this->belongsTo(School::class, 'schools_id');
    }

    public function person()
    {
        return $this->belongsTo(Person::class, 'people_id');
    }
}

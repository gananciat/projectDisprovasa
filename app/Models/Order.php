<?php

namespace App\Models;

use App\Models\Year;
use App\Models\Month;
use App\Models\Person;
use App\Models\School;
use App\Models\DetailOrder;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const ALIMENTACION = 'ALIMENTACION';
    const GRATUIDAD = 'GRATUIDAD';
    const UTILES = 'UTILES';
    const VALIJA_DIDACTICA = 'VALIJA DIDACTICA';

    protected $table = 'orders';
    protected $fillable = ['order','title','description','date','total','schools_id','aware','on_route',
                           'people_id','months_id','years_id','complete','type_order','code','repeat','balances_id','invoiced'];

    public function setOrderAttribute($value) {
        $this->attributes['order'] = mb_strtoupper($value);
    }

    public function setTitleAttribute($value) {
        $this->attributes['title'] = mb_strtoupper($value);
    }

    public function setDescriptionAttribute($value) {
        $this->attributes['description'] = mb_strtoupper($value);
    }

    public function addSelect($column)
    {
        $column = is_array($column) ? $column : func_get_args();
        $this->columns = array_merge((array) $this->columns, $column);
        return $this;
    }

    public function school()
    {
        return $this->belongsTO(School::class,'schools_id');
    }

    public function person()
    {
        return $this->belongsTo(Person::class,'people_id');
    }

    public function month()
    {
        return $this->hasOne(Month::class);
    }

    public function year()
    {
        return $this->hasOne(Year::class);
    }

    public function details()
    {
        return $this->hasMany(DetailOrder::class,'orders_id');
    }

    public function schools()
    {
        return $this->belongsTo(School::class);
    }

    public function people()
    {
        return $this->belongsTo(Person::class);
    }

    public function checks_delivery()
    {
        return $this->hasMany(CheckDeliveryMan::class, 'orders_id');
    }
}

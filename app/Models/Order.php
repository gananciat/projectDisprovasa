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

    protected $table = 'orders';
    protected $fillable = ['order','title','description','date','total','schools_id',
                           'people_id','months_id','years_id','complete','type_order'];

    public function setOrderAttribute($value) {
        $this->attributes['order'] = mb_strtoupper($value);
    }

    public function setTitleAttribute($value) {
        $this->attributes['title'] = mb_strtoupper($value);
    }

    public function setDescriptionAttribute($value) {
        $this->attributes['description'] = mb_strtoupper($value);
    }

    public function school()
    {
        return $this->hasOne(School::class);
    }

    public function person()
    {
        return $this->hasOne(Person::class);
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
        return $this->hasMany(DetailOrder::class);
    }

    public function schools()
    {
        return $this->belongsTo(School::class);
    }

    public function people()
    {
        return $this->belongsTo(Person::class);
    }
}

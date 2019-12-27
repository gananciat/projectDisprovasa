<?php

namespace App\Models;

use App\Models\Order;
use App\Models\Person;
use App\Models\Balance;
use App\Models\PhoneSchool;
use App\Models\Municipality;
use App\Models\PersonSchool;
use App\Models\CalendarSchool;
use App\Models\SchoolPresident;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $table = 'schools';
    protected $fillable = ['name','logo','direction','nit','code_high_school','code_primary','municipalities_id','people_id','current'];

    public function municipality()
    {
        return $this->hasOne(Municipality::class);
    }

    public function person()
    {
        return $this->hasOne(Person::class);
    }

    public function phons()
    {
        return $this->hasMany(PhoneSchool::class);
    }

    public function people()
    {
        return $this->belongsToMany(Person::class)->using(PersonSchool::class);        
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function calendar()
    {
        return $this->hasMany(CalendarSchool::class);
    }

    public function balance()
    {
        return $this->hasMany(Balance::class);
    }

    public function presidents()
    {
        return $this->hasMany(SchoolPresident::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

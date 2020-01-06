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
    protected $fillable = ['name','bill','logo','direction','nit','code_high_school','code_primary','municipalities_id','people_id','current','complete'];

    public function setNameAttribute($value) {
        $this->attributes['name'] = mb_strtoupper($value);
    }

    public function setBillAttribute($value) {
        $this->attributes['bill'] = mb_strtoupper($value);
    }

    public function setDirectionAttribute($value) {
        $this->attributes['direction'] = mb_strtoupper($value);
    }

    public function setNitAttribute($value) {
        $this->attributes['nit'] = mb_strtoupper($value);
    }

    public function setCodeHighSchoolAttribute($value) {
        $this->attributes['code_high_school'] = mb_strtoupper($value);
    }

    public function setCodePrimaryAttribute($value) {
        $this->attributes['code_primary'] = mb_strtoupper($value);
    }

    public function municipality()
    {
        return $this->belongsTO(Municipality::class,'municipalities_id');
    }

    public function person()
    {
        return $this->belongsTO(Person::class,'people_id');
    }

    public function phons()
    {
        return $this->hasMany(PhoneSchool::class,'schools_id');
    }

    public function people()
    {
        return $this->hasMany(PersonSchool::class,'schools_id');        
    }

    public function orders()
    {
        return $this->hasMany(Order::class,'schools_id');
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
        return $this->hasMany(SchoolPresident::class,'schools_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

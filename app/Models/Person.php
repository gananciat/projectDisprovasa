<?php

namespace App\Models;

use App\User;
use App\Models\Order;
use App\Models\School;
use App\Models\Balance;
use App\Models\PhonePerson;
use App\Models\Municipality;
use App\Models\PersonSchool;
use App\Models\SchoolPresident;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $code = 'UTF-8';
    protected $table = 'people';
    protected $fillable = ['cui','name_one','name_two','last_name_one','last_name_two',
                          'direction','email','municipalities_id'];

    public function setCuiAttribute($value) {
        $this->attributes['cui'] = mb_strtoupper($value);
    }

    public function setNameOneAttribute($valor) {
        $this->attributes['name_one'] = mb_strtoupper($valor);  
    }

    public function setNameTwoAttribute($valor) {
        $this->attributes['name_two'] = mb_strtoupper($valor);  
    }

    public function setLastNameOneAttribute($valor) {
        $this->attributes['last_name_one'] = mb_strtoupper($valor);  
    }

    public function setLastNameTwoAttribute($valor) {
        $this->attributes['last_name_two'] = mb_strtoupper($valor);  
    }

    public function setDirectionAttribute($valor) {
        $this->attributes['direction'] = mb_strtoupper($valor);  
    }

    public function setEmailAttribute($valor) {
        $this->attributes['email'] = mb_strtoupper($valor);  
    }

    public function municipality()
    {
        return $this->belongsTO(Municipality::class,'municipalities_id');
    }

    public function user()
    {
        return $this->hasOne(User::class,'people_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function phons()
    {
        return $this->hasMany(PhonePerson::class,'people_id');
    }

    public function schools()
    {
        return $this->belongsTo(PersonSchool::class);        
    }

    public function current_school()
    {
        return $this->hasOne(PersonSchool::class,'people_id')->where('current',true);        
    }

    public function school_president()
    {
        return $this->hasMany(SchoolPresident::class,'people_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function balance()
    {
        return $this->belongsTo(Balance::class);
    }
}

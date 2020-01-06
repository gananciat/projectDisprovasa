<?php

namespace App\Models;

use App\Models\Person;
use App\Models\Company;
use Illuminate\Database\Eloquent\Model;

class PhonePerson extends Model
{
    protected $table = 'phone_people';
    protected $fillable = ['number','companies_id','people_id'];

    public function company()
    {
        return $this->belongsTo(Company::class,'companies_id');
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}

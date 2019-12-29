<?php

namespace App\Models;

use App\Models\School;
use App\Models\Person;
use Illuminate\Database\Eloquent\Model;

class PersonSchool extends Model
{
    const DIRECTOR = 'DIRECTOR';
    const PROFESOR = 'PROFESOR';
    const OTRO = 'OTRO';

    protected $table = 'person_schools';
    protected $fillable = ['type_person','current','schools_id','people_id'];

    public function setTypePersonAttribute($value) {
        $this->attributes['type_person'] = mb_strtoupper($value);
    }   

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function person()
    {
        return $this->belongsTO(Person::class,'people_id');
    }
}

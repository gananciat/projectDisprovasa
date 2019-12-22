<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonSchool extends Model
{
    const DIRECTOR = 'DIRECTOR';
    const PROFESOR = 'PROFESOR';
    const OTRO = 'OTRO';

    protected $table = 'person_schools';
    protected $fillable = ['type_person','current','schools_id','people_id'];
}

<?php

namespace App\Models;

use App\Models\Person;
use App\Models\School;
use Illuminate\Database\Eloquent\Model;

class SchoolPresident extends Model
{
    protected $table = 'school_presidents';
    protected $fillable = ['schools_id','people_id','current'];

    public function person()
    {
        return $this->hasOne(Person::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}

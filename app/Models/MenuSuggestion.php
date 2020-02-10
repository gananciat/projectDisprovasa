<?php

namespace App\Models;

use App\Models\Person;
use App\Models\DetailSuggestion;
use Illuminate\Database\Eloquent\Model;

class MenuSuggestion extends Model
{
    protected $table = 'menu_suggestions';
    protected $fillable = ['title','description','people_id','current'];

    public function setTitleAttribute($value) {
        $this->attributes['title'] = mb_strtoupper($value);
    }

    public function setDescriptionAttribute($value) {
        $this->attributes['description'] = mb_strtoupper($value);
    }

    public function person()
    {
        return $this->belongsTo(Person::class,'people_id');
    }

    public function details()
    {
        return $this->hasMany(DetailSuggestion::class,'menu_suggestions_id');
    }
}

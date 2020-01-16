<?php

namespace App\Models;

use App\Models\Person;
use App\Models\DetailSuggestion;
use Illuminate\Database\Eloquent\Model;

class MenuSuggestion extends Model
{
    protected $table = 'menu_suggestions';
    protected $fillable = ['title','description','people_id'];

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

    public function person()
    {
        return $this->belongsTo(Person::class,'people_id');
    }

    public function details()
    {
        return $this->hasMany(DetailSuggestion::class,'orders_id');
    }
}

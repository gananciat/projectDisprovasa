<?php

namespace App\Models;

use App\Models\Category;
use App\Models\DetailOrder;
use App\Models\Presentation;
use App\Models\Price;
use App\Models\Quantify;
use App\Models\Sentence;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    const ALIMENTACION = 'ALIMENTACION';
    const GRATUIDAD = 'GRATUIDAD';
    const UTILES = 'UTILES';

    protected $table = 'products';
    protected $fillable = ['name','camouflage','categories_id','presentations_id','propierty','stock','stock_temporary'];
    
    public function setNameAttribute($value) {
        $this->attributes['name'] = mb_strtoupper($value);
    }
    
    public function setPropiertyAttribute($value) {
        $this->attributes['propierty'] = mb_strtoupper($value);
    }

    public function category()
    {
        return $this->belongsTo(Category::class,'categories_id');
    }

    public function presentation()
    {
        return $this->belongsTo(Presentation::class,'presentations_id');
    }

    public function prices()
    {
        return $this->hasMany(Price::class,'products_id');
    }

    public function sentences()
    {
        return $this->hasMany(Sentence::class);
    }

    public function detail()
    {
        return $this->belongsTo(DetailOrder::class);
    }

    public function quantify()
    {
        $year = Carbon::now()->year;
        return $this->hasOne(Quantify::class,'products_id')->where('year',$year);
    }
}

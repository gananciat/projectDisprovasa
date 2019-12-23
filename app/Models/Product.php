<?php

namespace App\Models;

use App\Models\Price;
use App\Models\Category;
use App\Models\Sentence;
use App\Models\DetailOrder;
use App\Models\Presentation;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = ['name','camouflage','categories_id','presentations_id'];

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
}

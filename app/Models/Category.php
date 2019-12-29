<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = ['name','description'];

    public function setNameAttribute($value) {
        $this->attributes['name'] = mb_strtoupper($value);
    }

    public function setDescriptionAttribute($value) {
        $this->attributes['description'] = mb_strtoupper($value);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }    
}

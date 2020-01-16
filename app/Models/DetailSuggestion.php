<?php

namespace App\Models;

use App\Models\Product;
use App\Models\MenuSuggestion;
use Illuminate\Database\Eloquent\Model;

class DetailSuggestion extends Model
{
    protected $table = 'detail_suggestions';
    protected $fillable = ['observation','products_id','menu_suggestions_id'];

    public function setObservationAttribute($valor) {
        $this->attributes['observation'] = strtoupper($valor);    
    }

    public function product()
    {
        return $this->belongsTo(Product::class,'products_id');
    }

    public function suggestion()
    {
        return $this->belongsTo(MenuSuggestion::class);
    } 
}

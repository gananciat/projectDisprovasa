<?php

namespace App\Models;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Model;

class Vat extends Model
{
   protected $table = 'vats';
   protected $fillable = ['value','actual'];

   public function invoices(){
   	return $this->hasMany(Invoice::class);
   }
    
      
}

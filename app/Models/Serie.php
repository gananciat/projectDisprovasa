<?php

namespace App\Models;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Model;

class Serie extends Model
{
    protected $table = 'series';
    protected $fillable = ['serie','total','init','expiration_date','actual_bill','active'];

   public function invoices(){
   	return $this->hasMany(Invoice::class);
   }
}

<?php

namespace App\Models;

use App\Models\Vat;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
   protected $table = 'invoices';
   protected $fillable = ['total','order_id','bill','vat_id','serie_id','date','total_iva','cancel'];

   public function vat(){
   	return $this->belongsTo(Vat::class);
   }

   public function serie(){
   	return $this->belongsTo(Serie::class);
   }

   public function order(){
   	return $this->belongsTo(Order::class);
   }
}

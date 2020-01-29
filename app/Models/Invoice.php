<?php

namespace App\Models;

use App\Models\Vat;
use App\Models\InvoiceProduct;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
   protected $table = 'invoices';
   protected $fillable = ['total','order_id','invoice','vat_id','serie_id','date','total_iva','cancel'];

   public function vat(){
   	return $this->belongsTo(Vat::class);
   }

   public function serie(){
   	return $this->belongsTo(Serie::class);
   }

   public function order(){
   	return $this->belongsTo(Order::class);
   }

   public function products()
   {
      return $this->hasMany(InvoiceProduct::class);
   }
}

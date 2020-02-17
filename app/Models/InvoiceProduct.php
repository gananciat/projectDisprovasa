<?php

namespace App\Models;
use App\Models\Invoice;
use App\Models\ProgressOrder;
use Illuminate\Database\Eloquent\Model;

class InvoiceProduct extends Model
{
    protected $table = 'invoice_products';
    protected $fillable = ['invoiced_as','invoice_id','progress_order_id'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function progress()
    {
    	return $this->belongsTo(ProgressOrder::class,'progress_order_id');
    }
}

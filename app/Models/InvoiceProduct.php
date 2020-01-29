<?php

namespace App\Models;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Model;

class InvoiceProduct extends Model
{
    protected $table = 'invoice_products';
    protected $fillable = ['invoiced_as','invoice_id','progress_order_id'];

    public function invoice($column)
    {
        return $this->belongsTo(Invoice::class);
    }
}

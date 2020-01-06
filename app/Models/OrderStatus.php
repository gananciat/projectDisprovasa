<?php

namespace App\Models;

use App\Models\ProgressOrder;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    const PEDIDO = 'PEDIDO';
    const EN_PROCESO = 'EN PROCESO';
    const COMPLETADO = 'COMPLETADO';
    
    protected $table = 'order_statuses';
    protected $fillable = ['status'];

    public function setStatusAttribute($valor) {
        $this->attributes['status'] = strtoupper($valor);    
    }

    public function progress()
    {
        return $this->belongsTo(ProgressOrder::class);
    }     
}

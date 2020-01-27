<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $invoices = Invoice::all();
        return $this->showAll($invoices);
    }

    public function store(Request $request)
    {
        $rules = [
            'bill' => 'required|integer',
            'vat_id' => 'required|integer|exists:vats,id',
            'serie_id' => 'required|integer|exists:series,id',
            'order_id' => 'required|integer|exists:orders,id',
            'date' => 'required|date',
            'total' => 'required|decimal',
            'total_iva' => 'required|decimal'
        ];

        $this->validate($request, $rules);

        DB::beginTransaction();
        
            $data = $request->all();
            $order = Order::find('order_id');
            $order->invoiced = true;
            $order->save();

        DB::commit();
    }

    public function show(invoice $invoice)
    {
        return $this->showOne($invoice);
    }

    public function update(Request $request, invoice $invoice)
    {
        $rules = [
            'date' => 'required|date'
        ];

        $this->validate($request, $rules);

        $invoice->total = $request->total;

        if (!$invoice->isDirty()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $invoice->save();
        return $this->showOne($invoice,201);
    }

    public function destroy(invoice $invoice)
    {
        $invoice->delete();
        return $this->showOne($invoice,201);
    }

    public function cancel($id)
    {
        DB::beginTransaction();

        DB::commit();
    }
}

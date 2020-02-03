<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Serie;
use App\Models\InvoiceProduct;
use App\Models\ProgressOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends ApiController
{
    public function __construct()
    {
        //parent::__construct();
    }

    public function index()
    {
        $invoices = Invoice::with('vat','serie','order','order.school','products')->get();
        return $this->showAll($invoices);
    }

    public function store(Request $request)
    {
        $rules = [
            'invoice' => 'required|integer',
            'vat_id' => 'required|integer|exists:vats,id',
            'serie_id' => 'required|integer|exists:series,id',
            'order_id' => 'required|integer|exists:orders,id',
            'date' => 'required|date',
            'total' => 'required',
            'total_iva' => 'required'
        ];

        $this->validate($request, $rules);

        DB::beginTransaction();
            $data = $request->all();
            $invoice = Invoice::create($data);

            $serie = Serie::find($request->serie_id);
            $serie->actual_bill += 1;
            $serie->save();

            foreach ($request->details as $d) {
                $d['invoice_id'] = $invoice->id;
                $progress_order = ProgressOrder::find($d['progress']['id']);
                $progress_order->check = 1;
                $d['progress_order_id'] = $progress_order->id;
                $invoice_product = InvoiceProduct::create($d);
                $progress_order->save();
            }

            $order = Order::find($request->order_id);
            $details_check = $order->details()->with('progress')->get()->where('progress.check',false)->values();

            if(count($details_check) == 0){
                $order->invoiced = true;
                $order->save();
            }

        DB::commit();
        return $this->showOne($invoice,201);
    }

    public function show(Invoice $invoice)
    {
        $invoice = Invoice::where('id',$invoice->id)->with('products.progress.detail','serie','vat','products.progress.product','order.school')->first();
        return $this->showOne($invoice);
    }

    public function update(Request $request, invoice $invoice)
    {
        $rules = [
            'date' => 'required|date'
        ];

        $this->validate($request, $rules);
        DB::beginTransaction();
            $invoice->date = $request->date;

            foreach ($request->details as $p) {
                $invoice_product = InvoiceProduct::find($p['id']);
                $invoice_product->invoiced_as = $p['invoiced_as'];
                $invoice_product->save();
            }

            $invoice->save();
        DB::commit();
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
            $invoice = Invoice::find($id);
            $invoice->cancel = true;
            $invoice->save();
            $products = $invoice->products()->with('progress')->get();

            foreach ($products as $p) {
                $progress = $p->progress;
                $progress->check = false;
                $progress->save();
            }

            $order = Order::find($invoice->order->id);
            $order->invoiced = false;
            $order->save();
        DB::commit();
        return $this->showOne($invoice,201);
    }

    public function invoice($id,$total)
    {
        $invoice = Invoice::where('id',$id)->with('products.progress.detail','serie','vat','products.progress.product','order.school')->first();
        $pdf = \PDF::loadView('pdfs.invoice',['invoice'=>$invoice,'total'=>$total]);
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('invoice.pdf');
    }
}

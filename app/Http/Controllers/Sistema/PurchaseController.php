<?php

namespace App\Http\Controllers\Sistema;

use Carbon\Carbon;
use App\Models\Quantify;
use App\Models\Purcharse;
use App\Models\ProductExpiration;
use Illuminate\Http\Request;
use App\Models\PurcharseDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use App\Models\Product;

class PurchaseController extends ApiController
{
    public function __construct()
    {
        //parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $purchases = Purcharse::with('provider')->orderBy('date','desc')->get();
        return $this->showAll($purchases);
    }


    //mostrar compra
    public function show(Purcharse $purchase){
        $purchase = Purcharse::where('id',$purchase->id)->with('details.product','provider.phones','provider.municipality.departament')->first();
        return $this->showOne($purchase);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'date' => 'required',
            'provider_id' => 'required|integer|exists:provider,id',
            'no_prof' => 'required',
            'details' => 'required'
        ];
        
        $this->validate($request, $rules);

        DB::beginTransaction();
            $data = $request->all();
            $purcharse = Purcharse::create($data);

            foreach ($request->details as $detail) {
                PurcharseDetail::create([
                    'purcharse_id'=>$purcharse->id,
                    'product_id'=>$detail['product_id'],
                    'quantity' => $detail['quantity'],
                    'expiry_date' => $detail['expiry_date'],
                    'purcharse_price' => $detail['purcharse_price']
                ]);

                $year = Carbon::now()->year;
                $quantify = Quantify::where('products_id', $detail['product_id'])->where('year',$year)->first();

                //Consultar Stock
                $consultar = Product::find($detail['product_id']);
                $consultar->stock += $detail['quantity'];
                $consultar->stock_temporary += $detail['quantity'];

                if(!$consultar->persevering){
                    $expiry = ProductExpiration::where('products_id',$consultar->id)->where('date',$detail['expiry_date'])->first();

                    if(is_null($expiry)){
                        $expiry = new ProductExpiration;
                    }

                    $expiry->quantity += $detail['quantity'];
                    $expiry->date = $detail['expiry_date'];
                    $expiry->products_id = $consultar->id;
                    $expiry->save();
                }

                $consultar->save();

                if(!is_null($quantify)){
                    $quantify->sumary_purchase = $quantify->sumary_purchase + $detail['quantity'];
                    $summary = $quantify->sumary_schools - $consultar->stock;
                    $quantify->subtraction = $summary > 0 ? $summary : 0;

                    $quantify->save();

                }
            }

        DB::commit();

        return $this->showOne($purcharse,201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Purcharse  $purcharse
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Purcharse $purchase)
    {
        DB::beginTransaction();
            $purchase->cancel = true;
            $details = $purchase->details;

            foreach ($details as $detail) {
                $year = Carbon::parse($purchase->date)->year;
                $quantify = Quantify::where('products_id', $detail->product_id)->where('year',$year)->first();
                if(!is_null($quantify)){

                    //Consultar Stock
                    $consultar = Product::find($detail->product_id);
                    $consultar->stock -= $detail->quantity;
                    $consultar->stock_temporary -= $detail->quantity;

                    if(!$consultar->persevering){
                        $expiry = ProductExpiration::where('products_id',$consultar->id)->where('date',$detail->expiry_date)->first();
                        if($expiry){
                            $expiry->quantity -= $detail->quantity;
                            $expiry->save();
                        }
                    }

                    $consultar->save();

                    $quantify->sumary_purchase = $quantify->sumary_purchase - $detail->quantity;
                    $summary = $quantify->sumary_schools - $consultar->stock;
                    $quantify->subtraction = $summary > 0 ? $summary : 0;
                    $quantify->save();
                }
            }     
            $purchase->save();
        DB::commit();

        return $this->showOne($purchase,201);
    }

    /**
     * Eliminar unicamente producto en el detalle de la compra.
     */
    public function detroyDetail($id)
    {
        DB::beginTransaction();
            $detail = PurcharseDetail::where('id',$id)->with('purchase')->first();

            $year = Carbon::parse($detail->purchase->date)->year;
            $quantify = Quantify::where('products_id', $detail->product_id)->where('year',$year)->first();

            if(!is_null($quantify)){
                //Consultar Stock
                $consultar = Product::find($detail->product_id);
                $consultar->stock -= $detail->quantity;
                $consultar->stock_temporary -= $detail->quantity;

                if(!$consultar->persevering){
                    $expiry = ProductExpiration::where('products_id',$consultar->id)->where('date',$detail->expiry_date)->first();
                    if(!is_null($expiry)){
                        $expiry->quantity -= $detail->quantity;
                        $expiry->save();
                    }
                }

                $consultar->save();

                $quantify->sumary_purchase = $quantify->sumary_purchase - $detail->quantity;
                $summary = $quantify->sumary_schools - $consultar->stock;
                $quantify->subtraction = $summary > 0 ? $summary : 0;
                $quantify->save();
            }

            $detail->delete();
            $purchase = Purcharse::find($detail->purcharse_id);
            $purchase->total -= $detail->quantity * $detail->purcharse_price;
            $purchase->save();

        DB::commit();

        return $this->showOne($detail,201);
    }

    public function updateDetails(Request $request)
    {
        DB::beginTransaction();
            foreach ($request->items as $item ) {
                $detail = PurcharseDetail::where('id',$item['id'])->with('purchase')->first();
                $detail->decrease = $item['decrease'];
                $detail->purcharse_price = $item['purcharse_price'];
                $detail->expiry_date = $item['expiry_date'];

                $year = Carbon::parse($detail->purchase->date)->year;
                $quantify = Quantify::where('products_id', $detail->product_id)->where('year',$year)->first();

                if($item['quantity'] != $detail['quantity']){

                    $consultar = Product::find($detail['product_id']); //obtener producto

                    $stock = $consultar->stock - $detail->quantity;
                    $stock_temporary = $consultar->stock_temporary - $detail->quantity;

                    $consultar->stock = $stock > 0 ? $stock : 0;//descontar cantidad anterior a stock
                    $consultar->stock_temporary = $stock_temporary > 0 ? $stock_temporary : 0;//descontar cantidad anterior a stock temporal anterior

                    $consultar->stock += $item['quantity'];//agregar nueva cantidad a stock
                    $consultar->stock_temporary += $item['quantity'];//agregar nueva cantidad a stock temporal


                    if(!$consultar->persevering){
                        $expiry = ProductExpiration::where('products_id',$consultar->id)->where('date',$detail->expiry_date)->first();
                        if(!is_null($expiry)){
                            $expiry->quantity -= $detail->quantity; //descontar cantidad expirada anterior
                            $expiry->quantity += $item['quantity']; //agregar nueva cantidad
                            $expiry->save();
                        } 
                    }

                    $consultar->save();
                    $purchases = $quantify->sumary_purchase - $detail->quantity;
                    $quantify->sumary_purchase = $purchases > 0 ? $purchases : 0;

                    $quantify->sumary_purchase = $quantify->sumary_purchase + $item['quantity'];

                    $summary = $quantify->sumary_schools - $consultar->stock;
                    $quantify->subtraction = $summary > 0 ? $summary : 0;
                    $quantify->save();

                    $detail->quantity = $item['quantity'];  
                }
                $detail->save();
            }

            $this->updateTotalPurcharse($request->purchase_id, $request->total);
        DB::commit();

        return $this->showQuery($request->items);
    }

    public function updateTotalPurcharse($id, $total)
    {
        $purchase = Purcharse::find($id);
        $purchase->total = $total;
        $purchase->save();
    }
}

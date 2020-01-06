<?php

namespace App\Http\Controllers\Sistema;

use Carbon\Carbon;
use App\Models\Quantify;
use App\Models\Purcharse;
use Illuminate\Http\Request;
use App\Models\PurcharseDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class PurchaseController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
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
                    'purcharse_price' => $detail['purcharse_price']
                ]);

                $year = Carbon::now()->year;
                $quantify = Quantify::where('products_id', $detail['product_id'])->where('year',$year)->first();

                if(!is_null($quantify)){
                    $quantify->sumary_purchase = $quantify->sumary_purchase + $detail['quantity'];
                    $summary = $quantify->sumary_schools - $quantify->summary_purcharse;
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
                    $quantify->sumary_purchase = $quantify->sumary_purchase - $detail->quantity;
                    $summary = $quantify->sumary_schools - $quantify->summary_purcharse;
                    $quantify->subtraction = $summary > 0 ? $sumary : 0;
                    $quantify->save();
                }
            }     
            $purchase->save();
        DB::commit();

        return $this->showOne($purchase,201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purcharse  $purcharse
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purcharse $purchase)
    {

    }
}

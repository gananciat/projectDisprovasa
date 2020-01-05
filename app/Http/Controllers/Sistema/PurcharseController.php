<?php

namespace App\Http\Controllers\Sistema;

use App\Models\Quantify;
use App\Models\Purcharse;
use Illuminate\Http\Request;
use App\Models\PurcharseDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class PurcharseController extends ApiController
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
        $purcharses = Purcharse::with('provider')->get();
        return $this->showAll($purcharses);
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
            'name' => 'required|string|max:75',
            'categories_id' => 'required|integer|exists:categories,id',
            'presentations_id' => 'required|integer|exists:presentations,id',
            'price' => 'required|numeric|between:0.01,100000',
            'camouflage' => 'required|boolean', 
            'propierty' => 'required|string'
        ];
        
        $this->validate($request, $rules);

        DB::beginTransaction();
            $data = $request->all();
            $purcharse = Purcharse::create($data);

            foreach ($details as $detail) {
                PurcharseDetail::create([
                    'purcharse_id'=>$purcharse->id,
                    'product_id'=>$detail['product_id'],
                    'quantity' => $detail['quantity']
                ]);

                $quantify = Quantify::where('product_id',$detail['product_id'])->first();
                if(!is_null($quantity)){
                    $quantify->summary_purcharse = $detail[$quantity];
                    $summary = $quantify->sumary_schools - $quantify->summary_purcharse;
                    $quantify->substraction = $summary > 0 ? $sumary : 0;
                    $quantify->save();
                }
            }

        DB::commit();

        return $this->showOne($purcharse,201);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Purcharse  $purcharse
     * @return \Illuminate\Http\Response
     */
    public function edit(Purcharse $purcharse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Purcharse  $purcharse
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Purcharse $purcharse)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purcharse  $purcharse
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purcharse $purcharse)
    {
        //
    }
}

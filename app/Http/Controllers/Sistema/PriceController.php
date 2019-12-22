<?php

namespace App\Http\Controllers\Sistema;

use App\Models\Price;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class PriceController extends ApiController
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = [
            'products_id.exists'    => 'Debe de seleccionar la menos un producto.',
            'price.between' => 'El precio unitario debe de estar en el rango de :min hasta :max.',
        ];


        $rules = [
            'products_id' => 'required|integer|exists:products,id',
            'price' => 'required|numeric|between:0.01,100000'
        ];
        
        $this->validate($request, $rules, $messages);

        try {
            DB::beginTransaction();
                $data = $request->all();

                $precios = Price::where('products_id',$data->id)->where('current',true)->get();

                foreach ($precios as $key => $value) {
                    $value->current = false;
                    $value->save();
                }

                $insert = new Price();
                $insert->price = $data->price;
                $insert->current = true;
                $insert->products_id = $data->products_id;
                $insert->save();
            DB::commit();

            return $this->showOne($insert,201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(),403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function show(Price $price)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function edit(Price $price)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Price $price)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function destroy(Price $price)
    {
        $price->delete();
        return $this->showOne($price,201);
    }
}

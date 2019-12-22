<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Price;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends ApiController
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
        return $this->showAll(Product::with('category','presentation','prices')->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->showAll(Product::with('category','presentation',['prices' => function ($query) {
            $query->where('current', '=', true);
        }])->get());
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
            'categories_id.exists'    => 'Debe de seleccionar la menos una categoría.',
            'presentations_id.exists'    => 'Debe de seleccionar la menos una presentación.',
            'price.between' => 'El precio unitario debe de estar en el rango de :min hasta :max.',
        ];


        $rules = [
            'name' => 'required|string|max:75',
            'categories_id' => 'required|integer|exists:categories,id',
            'presentations_id' => 'required|integer|exists:presentations,id',
            'price' => 'required|numeric|between:0.01,100000',
            'camouflage' => 'required|boolean', 
        ];
        
        $this->validate($request, $rules, $messages);

        try {
            DB::beginTransaction();
                $data = $request->all();

                $existe = Product::where([
                    ['name', '=', $data->name],
                    ['categories_id', '=', $data->categories_id],
                    ['presentations_id', '=', $data->presentations_id],
                ])->first();
                
                if(!is_null($existe))
                    return $this->errorResponse('El producto que trata de ingresar ya existe.',403);

                $insert = Product::create($data);

                $precios = Price::where('products_id',$insert->id)->where('current',true)->get();

                foreach ($precios as $key => $value) {
                    $value->current = false;
                    $value->save();
                }

                $asignar_precio = new Price();
                $asignar_precio->price = $data->price;
                $asignar_precio->current = true;
                $asignar_precio->products_id = $insert->id;
                $asignar_precio->save();
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
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return $this->showAll(Product::with('category','presentation','prices')->where('id',$product->id)->get());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $messages = [
            'categories_id.exists'    => 'Debe de seleccionar la menos una categoría.',
            'presentations_id.exists'    => 'Debe de seleccionar la menos una presentación.',
        ];

        $rules = [
            'name' => 'required|string|max:75',
            'categories_id' => 'required|integer|exists:categories,id',
            'presentations_id' => 'required|integer|exists:presentations,id',
            'camouflage' => 'required|boolean', 
        ];
        
        $this->validate($request, $rules, $messages);

        try {
            DB::beginTransaction();
                $data = $request->all();

                $existe = Product::where([
                    ['name', '=', $data->name],
                    ['categories_id', '=', $data->categories_id],
                    ['presentations_id', '=', $data->presentations_id],
                ])->first();
                
                if(!is_null($existe))
                    return $this->errorResponse('El producto que trata de ingresar ya existe.',403);

                $product->name = $data->name;
                $product->categories_id = $data->categories_id;
                $product->presentations_id = $data->presentations_id;
                $product->camouflage = $data->camouflage;

                if (!$product->isDirty()) {
                    return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
                }

                $product->save();
            DB::commit();

            return $this->showOne($product,201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(),403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return $this->showOne($product,201);
    }
}

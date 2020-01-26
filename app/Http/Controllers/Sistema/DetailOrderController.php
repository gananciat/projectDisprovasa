<?php

namespace App\Http\Controllers\Sistema;

use App\Models\Order;
use App\Models\School;
use App\Models\Balance;
use App\Models\Product;
use App\Models\Quantify;
use App\Models\DetailOrder;
use App\Models\OrderStatus;
use App\Models\Disbursement;
use Illuminate\Http\Request;
use App\Models\ProgressOrder;
use App\Models\ProductExpiration;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use Illuminate\Database\Eloquent\Builder;

class DetailOrderController extends ApiController
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
            'orders_id.exists'    => 'Debe de seleccionar la menos una orden.',
            'products_id.exists'    => 'Debe de seleccionar la menos un producto para la orden.',
        ];

        $rules = [
            'orders_id' => 'required|integer|exists:orders,id',
            'quantity' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'observation' => 'nullable|max:125',
            'products_id' => 'required|integer|exists:products,id',
        ];
        
        $this->validate($request, $rules, $messages);

        try {
            DB::beginTransaction();

                $order = Order::find($request->orders_id);
                $estado_orden = OrderStatus::where('status',OrderStatus::PEDIDO)->first();

                $balance = Balance::where([
                    ['code',$order->code],
                    ['schools_id',$order->schools_id],
                    ['type_balance',$order->type_order],
                    ['current',true]
                ])->first();

                $insert_detalle_orden = new DetailOrder();
                $insert_detalle_orden->quantity = $request->quantity;
                $insert_detalle_orden->sale_price = $request->sale_price;
                $insert_detalle_orden->subtotal = $request->quantity*$request->sale_price;
                $insert_detalle_orden->observation = $request->observation;
                $insert_detalle_orden->products_id = $request->products_id;
                $insert_detalle_orden->orders_id = $request->orders_id;
                $insert_detalle_orden->complete = false;
                $insert_detalle_orden->save();

                $insert_progreso_orden = new ProgressOrder();
                $insert_progreso_orden->purchased_amount = 0;
                $insert_progreso_orden->original_quantity = $insert_detalle_orden->quantity;
                $insert_progreso_orden->order_statuses_id = $estado_orden->id;
                $insert_progreso_orden->detail_orders_id = $insert_detalle_orden->id;
                $insert_progreso_orden->products_id = $insert_detalle_orden->products_id;
                $insert_progreso_orden->save();

                $product = Product::find($insert_detalle_orden->products_id);                 
                $insert_quantify = Quantify::where('products_id',$insert_detalle_orden->products_id)->where('year',date('Y'))->first();

                if($insert_detalle_orden->quantity > 1){
                    for ($i=0; $i < $insert_detalle_orden->quantity; $i++) { 
                        if($product->stock_temporary > 0){
                            $product->stock_temporary -= 1;
                            
                            if(!$product->persevering)
                            {
                                $expiration = ProductExpiration::where('products_id',$product->id)->where('expiration',false)->where('current',true)->latest()->orderBy('date', 'asc')->first();
                                $expiration->used += 1;
                
                                if($expiration->used == 0)
                                    $expiration->current = false;
                    
                                $expiration->save();
                            }

                        }else{
                            $insert_quantify->subtraction += 1;
                        }
                    }
                }else{
                    if($product->stock_temporary > 0){
                        $product->stock_temporary -= 1;
                        
                        if(!$product->persevering)
                        {
                            $expiration = ProductExpiration::where('products_id',$product->id)->where('expiration',false)->where('current',true)->latest()->orderBy('date', 'asc')->first();
                            $expiration->used += 1;
            
                            if($expiration->used == 0)
                                $expiration->current = false;
                
                            $expiration->save();
                        }

                    }else{
                        $insert_quantify->subtraction += 1;
                    }
                }

                $insert_quantify->sumary_schools +=  $insert_detalle_orden->quantity;
                $insert_quantify->save();
                $product->save();

                $order->total += $insert_detalle_orden->subtotal;
                $balance->subtraction_temporary += $insert_detalle_orden->subtotal;

                if($balance->balance == $balance->subtraction_temporary)
                    $balance->current = false;

                if(($balance->balance - $balance->subtraction_temporary) < 0)
                    return $this->errorResponse('El monto del pedido excede al monto disponible en el código '.$order->code, 422);

                $order->balances_id = $balance->id;
                $balance->save();
                $order->save();

            DB::commit();

            return $this->showOne($insert_detalle_orden, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(),403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DetailOrder  $detailOrder
     * @return \Illuminate\Http\Response
     */
    public function show($detail_order)
    {
        $detail_order = Order::select("id","order","title","description","type_order","date","total","complete","schools_id","people_id","created_at","code")
                                ->with(['person:id,cui,name_one,name_two,last_name_one,last_name_two',
                                        'school:id,name,municipalities_id',
                                        'school.municipality:id,name,departaments_id',
                                        'school.municipality.departament:id,name',
                                        'details:id,quantity,sale_price,subtotal,observation,complete,products_id,orders_id',
                                        'details.product:id,name,presentations_id,categories_id',
                                        'details.product.presentation:id,name',
                                        'details.product.category:id,name',
                                        'details.progress:id,purchased_amount,original_quantity,order_statuses_id,detail_orders_id',
                                        'details.progress.order_status:id,status'])
                                ->addSelect(['balance' => Balance::select('balance')
                                        ->whereColumn([
                                                        ['id', 'orders.balances_id'],
                                                      ])
                                        ->where('current', true)
                                        ->where('year', date('Y'))
                                        ->orderBy('disbursement_id','desc')
                                        ->limit(1)
                                    ])  
                                ->addSelect(['subtraction_temporary' => Balance::select('subtraction_temporary')
                                        ->whereColumn([
                                                        ['id', 'orders.balances_id'],
                                                      ])
                                        ->where('current', true)              
                                        ->where('year', date('Y'))
                                        ->orderBy('disbursement_id','desc')
                                        ->limit(1)
                                    ])    
                                ->addSelect(['disbursement_id' => Balance::select('disbursement_id')
                                        ->whereColumn([
                                                        ['id', 'orders.balances_id'],
                                                      ])
                                        ->where('current', true)              
                                        ->where('year', date('Y'))
                                        ->orderBy('disbursement_id','desc')
                                        ->limit(1)
                                    ])     
                                ->addSelect(['disbursement' => Disbursement::select('name')
                                        ->whereColumn([
                                                        ['id', 'disbursement_id']
                                                      ])
                                    ])                                      
                                ->withCount(['details as detail_complete' => function(Builder $query) {
                                    $query->where('complete', true);
                                }, 'details as detail_total'])
                                ->where('id',$detail_order)->get();

        return $this->showAll($detail_order);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DetailOrder  $detailOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(DetailOrder $detail_order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DetailOrder  $detailOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DetailOrder $detail_order)
    {
        $rules = [
            'quantity' => 'required|numeric',
            'observation' => 'nullable|max:125',
        ];
        
        $this->validate($request, $rules);

        try {
            DB::beginTransaction();

                $estado_orden = OrderStatus::where('status',OrderStatus::PEDIDO)->first();
                $order = Order::find($detail_order->orders_id);

                $balance = Balance::where([
                    ['code',$order->code],
                    ['schools_id',$order->schools_id],
                    ['type_balance',$order->type_order],
                    ['current',true]
                ])->first();

                $progress_order = ProgressOrder::where('detail_orders_id',$detail_order->id)->first();
                if(($request->quantity+1) > $progress_order->purchased_amount){

                    $product = Product::find($detail_order->products_id);                 
                    $insert_quantify = Quantify::where('products_id',$detail_order->products_id)->where('year',date('Y'))->first();

                    //Restamos el sub total anterior
                    $balance->subtraction_temporary -= $detail_order->subtotal;
                    $order->total = $order->total - $detail_order->subtotal;

                    if($detail_order->quantity > 1){
                        for ($i=0; $i < $detail_order->quantity; $i++) { 
                            
                            if($product->stock_temporary < $product->stock)
                            {
                                $product->stock_temporary += 1;
                                if(!$product->persevering)
                                {
                                    $expiration = ProductExpiration::where('products_id',$product->id)->where('expiration',false)->where('used','!=',0)->latest()->orderBy('date', 'desc')->first();
                                    $expiration->used -= 1;
                    
                                    if($expiration->used == 0)
                                        $expiration->current = true;
                        
                                    $expiration->save();
                                }
                            }
                            else
                                $insert_quantify->subtraction -= 1;
                        }
                    }else{
                        if($product->stock_temporary < $product->stock)
                        {
                            $product->stock_temporary += 1;
                            if(!$product->persevering)
                            {
                                $expiration = ProductExpiration::where('products_id',$product->id)->where('expiration',false)->where('used','!=',0)->latest()->orderBy('date', 'desc')->first();
                                $expiration->used -= 1;
                
                                if($expiration->used == 0)
                                    $expiration->current = true;
                    
                                $expiration->save();
                            }
                        }
                        else
                            $insert_quantify->subtraction -= 1;
                    }

                    $insert_quantify->sumary_schools -=  $detail_order->quantity;

                    if($balance->balance != $balance->subtraction_temporary)
                        $balance->current = true;

                    $detail_order->quantity = $request->quantity;
                    $detail_order->subtotal = $detail_order->quantity*$detail_order->sale_price;
                    $detail_order->observation = $request->observation;
                    $detail_order->complete = false;

                    //Asignamos el nuevo subtotal al total
                    $balance->subtraction_temporary += $detail_order->subtotal;
                    $order->total = $order->total + $detail_order->subtotal;

                    if($balance->balance == $balance->subtraction_temporary)
                        $balance->current = false;

                    if(($balance->balance - $balance->subtraction_temporary) < 0)
                        return $this->errorResponse('El monto del pedido excede al monto disponible en el código '.$order->code, 422);

                    $progress_order->order_statuses_id = $estado_orden->id;

                    if (!$detail_order->isDirty()) {
                        return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
                    }

                    if($detail_order->quantity > 1){
                        for ($i=0; $i < $detail_order->quantity; $i++) { 
                            if($product->stock_temporary > 0){
                                $product->stock_temporary -= 1;
                                
                                if(!$product->persevering)
                                {
                                    $expiration = ProductExpiration::where('products_id',$product->id)->where('expiration',false)->where('current',true)->latest()->orderBy('date', 'asc')->first();
                                    $expiration->used += 1;
                    
                                    if($expiration->used == $expiration->quantity)
                                        $expiration->current = false;
                        
                                    $expiration->save();
                                }
    
                            }else{
                                $insert_quantify->subtraction += 1;
                            }
                        }
                    }else{
                        if($product->stock_temporary > 0){
                            $product->stock_temporary -= 1;
                            
                            if(!$product->persevering)
                            {
                                $expiration = ProductExpiration::where('products_id',$product->id)->where('expiration',false)->where('current',true)->latest()->orderBy('date', 'asc')->first();
                                $expiration->used += 1;
                
                                if($expiration->used == $expiration->quantity)
                                    $expiration->current = false;
                    
                                $expiration->save();
                            }
    
                        }else{
                            $insert_quantify->subtraction += 1;
                        }
                    }

                    $insert_quantify->sumary_schools +=  $detail_order->quantity;
    
                    $insert_quantify->save();
                    $product->save();                    
                    $order->save();
                    $balance->save();
                    $detail_order->save();
                    $progress_order->save();
                } else {
                    return $this->errorResponse('La cantidad ingresada '.
                        $request->quantity.', no puede ser menor a la cantidad actual '.
                        $detail_order->quantity.'. La razón es porque el producto ya fue reservado.', 422);
                }

            DB::commit();

            return $this->showOne($detail_order, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(),403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DetailOrder  $detailOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(DetailOrder $detail_order)
    {
        try {

            DB::beginTransaction();

                $order = Order::find($detail_order->orders_id);

                $balance = Balance::where([
                    ['code',$order->code],
                    ['schools_id',$order->schools_id],
                    ['type_balance',$order->type_order],
                    ['current',true]
                ])->first();

                if($detail_order->complete == false){
                    ProgressOrder::where('detail_orders_id',$detail_order->id)->delete();
                    $balance->subtraction_temporary -= $detail_order->subtotal;
                    $order->total = $order->total - $detail_order->subtotal;
                    
                    $product = Product::find($detail_order->products_id);
                    
                    $insert_quantify = Quantify::where('products_id',$detail_order->products_id)->where('year',date('Y'))->first();
    
                    $insert_quantify->sumary_schools -=  $detail_order->quantity;
                    
                    if($detail_order->quantity > 1){
                        for ($i=0; $i < $detail_order->quantity; $i++) { 
                            
                            if($product->stock_temporary < $product->stock)
                            {
                                $product->stock_temporary += 1;
                                if(!$product->persevering)
                                {
                                    $expiration = ProductExpiration::where('products_id',$product->id)->where('expiration',false)->where('used','!=',0)->latest()->orderBy('date', 'desc')->first();
                                    $expiration->used -= 1;
                    
                                    if($expiration->used == 0)
                                        $expiration->current = true;
                        
                                    $expiration->save();
                                }
                            }
                            else
                                $insert_quantify->subtraction -= 1;
                        }
                    }else{
                        if($product->stock_temporary < $product->stock)
                        {
                            $product->stock_temporary += 1;
                            if(!$product->persevering)
                            {
                                $expiration = ProductExpiration::where('products_id',$product->id)->where('expiration',false)->where('used','!=',0)->latest()->orderBy('date', 'desc')->first();
                                $expiration->used -= 1;
                
                                if($expiration->used == 0)
                                    $expiration->current = true;
                    
                                $expiration->save();
                            }
                        }
                        else
                            $insert_quantify->subtraction -= 1;
                    }

                    if($balance->balance != $balance->subtraction_temporary)
                        $balance->current = true;

                    if(($balance->balance - $balance->subtraction_temporary) < 0)
                        return $this->errorResponse('El monto del pedido excede al monto disponible en el código '.$order->code, 422);                                     
                        
                    $insert_quantify->save();
                    $product->save();
                    $balance->save();
                    $detail_order->delete();
                } else {
                    return $this->errorResponse('No puede eliminar el detalle de la orden.',422);
                }

            DB::commit();

            return $this->showOne($detail_order, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(),403);
        }
    }
}

<?php

namespace App\Http\Controllers\Sistema;

use App\Models\Rol;
use App\Models\Year;
use App\Models\Month;
use App\Models\Order;
use App\Models\Balance;
use App\Models\Product;
use App\Models\Quantify;
use App\Models\DetailOrder;
use App\Models\OrderStatus;
use App\Models\PersonSchool;
use Illuminate\Http\Request;
use App\Models\ProgressOrder;
use App\Models\ProductExpiration;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;
use Illuminate\Database\Eloquent\Builder;

class OrderController extends ApiController
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
        if(Auth::user()->current_school == true){
            $person_school = PersonSchool::where([
                ['current','=',true],
                ['people_id','=',Auth::user()->people_id]
            ])->first();

            $orders = Order::where('schools_id',$person_school->schools_id)
                            ->orderBy('date','desc')
                            ->get();
        } else {
            $orders = Order::orderBy('date','desc')->get();
        }

        return $this->showAll($orders);
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
            'schools_id.exists'    => 'Debe de seleccionar la menos una escuela.',
            'detail_order.products_id.exists'    => 'Debe de seleccionar la menos un producto para la orden.',
            'title.unique'    => 'El título de la orden debe ser único.',
            'date.after_or_equal'    => 'La fecha tiene que ser igual o mayor a '.date('d/m/Y').'.',
        ];

        $rules = [
            'schools_id' => 'required|integer|exists:schools,id',
            'order' => 'required|string',
            'title' => 'required|string|max:125|unique:orders,title',
            'description' => 'required|string|max:1000',
            'date' => 'required|date|after_or_equal:'.date('Y-m-d'),
            'type_order' => 'required|string',
            'detail_order.quantity.*' => 'required|numeric',
            'detail_order.sale_price.*' => 'required|numeric',
            'detail_order.observation.*' => 'nullable|max:125',
            'detail_order.products_id.*' => 'required|integer|exists:products,id',
            'code' => 'required',
        ];
        
        $this->validate($request, $rules, $messages);

        try {

            $estado_orden = OrderStatus::where('status',OrderStatus::PEDIDO)->first();

            $mes = Month::find(date('n',strtotime($request->date)));
            $anio = Year::where('year',date('Y',strtotime($request->date)))->first();

            switch ($request->type_order) {
                case 'alimentacion':
                    $propierty = Order::ALIMENTACION;
                    break;
                case 'gratuidad':
                    $propierty = Order::GRATUIDAD;
                    break;
                case 'utiles':
                    $propierty = Order::UTILES;
                case 'valija didactica':
                    $propierty = Order::VALIJA_DIDACTICA;
                    break;
            }

            DB::beginTransaction();

                $insert_orden = new Order();
                $insert_orden->order = $request->order;
                $insert_orden->title = $request->title;
                $insert_orden->description = $request->description;
                $insert_orden->date = date('Y-m-d',strtotime($request->date));
                $insert_orden->schools_id = $request->schools_id;
                $insert_orden->type_order = $propierty;
                $insert_orden->people_id = Auth::user()->people_id;                
                $insert_orden->months_id = $mes->id;             
                $insert_orden->years_id = $anio->id;
                $insert_orden->code = $request->code;
                $insert_orden->save();

                $balance = Balance::where([
                    ['code',$insert_orden->code],
                    ['schools_id',$insert_orden->schools_id],
                    ['type_balance',$insert_orden->type_order],
                    ['current',true]
                ])->first();

                for ($i=0; $i < count($request->detail_order); $i++) { 
                    $insert_detalle_orden = new DetailOrder();
                    $insert_detalle_orden->quantity = $request->detail_order[$i]['quantity'];
                    $insert_detalle_orden->sale_price = $request->detail_order[$i]['sale_price'];
                    $insert_detalle_orden->subtotal = $request->detail_order[$i]['sale_price']*$request->detail_order[$i]['quantity'];
                    $insert_detalle_orden->observation = $request->detail_order[$i]['observation'];
                    $insert_detalle_orden->products_id = $request->detail_order[$i]['products_id'];
                    $insert_detalle_orden->orders_id = $insert_orden->id;
                    $insert_detalle_orden->complete = false;
                    $insert_detalle_orden->save();

                    $insert_orden->total += $insert_detalle_orden->subtotal;
                    $insert_orden->balances_id = $balance->id;
                    $insert_orden->save();

                    $insert_progreso_orden = new ProgressOrder();
                    $insert_progreso_orden->purchased_amount = 0;
                    $insert_progreso_orden->original_quantity = $insert_detalle_orden->quantity;
                    $insert_progreso_orden->order_statuses_id = $estado_orden->id;
                    $insert_progreso_orden->detail_orders_id = $insert_detalle_orden->id;
                    $insert_progreso_orden->products_id = $insert_detalle_orden->products_id;
                    $insert_progreso_orden->save();

                    $product = Product::find($insert_detalle_orden->products_id);
                    
                    $insert_quantify = Quantify::where('products_id',$insert_detalle_orden->products_id)->where('year',$anio->year)->first();

                    if($insert_detalle_orden->quantity > 1){
                        for ($i=0; $i < $insert_detalle_orden->quantity; $i++) { 
                            if($product->stock_temporary > 0){
                                $product->stock_temporary -= 1;
                                
                                if(!$product->persevering)
                                {
                                    $expiration = ProductExpiration::where('products_id',$product->id)->where('expiration',false)->where('current',true)->latest()->orderBy('date', 'asc')->first();
                                    if(!is_null($expiration))
                                    {
                                        $expiration->used -= 1;
                    
                                        if($expiration->used == 0)
                                            $expiration->current = false;
                            
                                        $expiration->save();
                                    }
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
                                if(!is_null($expiration))
                                {
                                    $expiration->used -= 1;
                
                                    if($expiration->used == 0)
                                        $expiration->current = false;
                        
                                    $expiration->save();
                                }
                            }

                        }else{
                            $insert_quantify->subtraction += 1;
                        }
                    }
                }

                $balance->subtraction_temporary += $insert_orden->total;

                if($balance->balance === $balance->subtraction_temporary)
                {
                    $balance->current = false;
                }

                if($balance->subtraction_temporary > $balance->balance){
                    return $this->errorResponse('El monto del pedido excede al monto disponible en el código '.$request->code, 422);
                }

                $insert_quantify->sumary_schools +=  $insert_detalle_orden->quantity;

                $insert_quantify->save();
                $product->save();
                $balance->save();

            DB::commit();

            return $this->showOne($insert_orden, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(),403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show($order)
    {
        if(Auth::user()->current_school == true){
            $person_school = PersonSchool::where([
                ['current','=',true],
                ['people_id','=',Auth::user()->people_id]
            ])->first();

            $orders = Order::withCount(['details as detail_complete' => function(Builder $query) {
                $query->where('deliver', true);
            }, 'details as detail_total'])->where('type_order',$order)
            ->where('schools_id',$person_school->schools_id)->orderBy('date','asc')->get();
        } else {
            $orders = Order::withCount(['details as detail_complete' => function(Builder $query) {
                $query->where('deliver', true);
            }, 'details as detail_total'])->where('type_order',$order)->orderBy('date','desc')->get();
        }
        return $this->showAll($orders, 201);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $messages = [
            'title.unique'    => 'El título de la orden debe ser único.',
        ];

        $rules = [
            'title' => 'required|string|max:125|unique:orders,title,'.$order->id,
            'description' => 'required|string|max:200',
            'date' => 'required|date',
        ];
        
        $this->validate($request, $rules, $messages);

        try {
            DB::beginTransaction();

                if(date('Y-m-d',strtotime($request->date)) < $order->date)
                    return $this->errorResponse('La fecha que desea actualizar es incorrecta.', 422);

                $mes = Month::find(date('n',strtotime($request->date)));
                $anio = Year::where('year',date('Y',strtotime($request->date)))->first();

                $order->order = $request->order;
                $order->title = $request->title;
                $order->description = $request->description;
                $order->date = date('Y-m-d',strtotime($request->date));
                $order->people_id = Auth::user()->people_id;                
                $order->months_id = $mes->id;             
                $order->years_id = $anio->id;

                if (!$order->isDirty()) {
                    return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
                }

                $order->save();
                
            DB::commit();

            return $this->showOne($order, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(),403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    //falta
    public function destroy(Order $order)
    {
        try {

            DB::beginTransaction();

                $balance = Balance::where([
                    ['code',$order->code],
                    ['schools_id',$order->schools_id],
                    ['type_balance',$order->type_order],
                    ['current',true]
                ])->first();

                if($balance->balance != $balance->subtraction_temporary)
                    $balance->current = true;                

                $balance->subtraction_temporary -= $order->total;
                $balance->save();

                $detalle_orden = DetailOrder::where([
                    ['orders_id','=',$order->id],
                    ['complete','=',true]
                ])->get();
                
                if(count($detalle_orden) == 0){
                    $detalle_orden = DetailOrder::where('orders_id','=',$order->id)->get();

                    foreach ($detalle_orden as $key => $value) {
                        ProgressOrder::where('detail_orders_id',$value->id)->delete();

                        $product = Product::find($value->products_id);
                        $insert_quantify = Quantify::where('products_id',$value->products_id)->where('year',date('Y'))->first();
    
                        if($value->quantity > 1)
                        {
                            for ($i=0; $i < $value->quantity; $i++) { 
                            
                                if($product->stock_temporary < $product->stock)
                                {
                                    $product->stock_temporary += 1;
                                    if(!$product->persevering)
                                    {
                                        $expiration = ProductExpiration::where('products_id',$product->id)->where('expiration',false)->where('used',0)->latest()->orderBy('date', 'desc')->first();
                                        $expiration->used += 1;
    
                                        if(!$expiration->current)
                                            $expiration->current = true;
                            
                                        $expiration->save();
                                    }
                                }
                                else
                                    $insert_quantify->subtraction -= 1;
                            }
                        }
                        else
                        {
                            if($product->stock_temporary < $product->stock)
                            {
                                $product->stock_temporary += 1;
                                if(!$product->persevering)
                                {
                                    $expiration = ProductExpiration::where('products_id',$product->id)->where('expiration',false)->where('used',0)->latest()->orderBy('date', 'desc')->first();
                                    $expiration->used += 1;

                                    if(!$expiration->current)
                                        $expiration->current = true;
                        
                                    $expiration->save();
                                }
                            }
                            else
                                $insert_quantify->subtraction -= 1;
                        }


                        $insert_quantify->sumary_schools -=  $value->quantity;

                        $product->save();
                        $insert_quantify->save();
                        $value->forceDelete();
                    }

                    $order->delete();
                } else {
                    return $this->errorResponse('No puede eliminar la orden, está orden se encuentra en proceso.',422);
                }

            DB::commit();

            return $this->showOne($order, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(),403);
        }        
    }
}

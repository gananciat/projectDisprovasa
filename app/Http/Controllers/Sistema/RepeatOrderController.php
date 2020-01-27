<?php

namespace App\Http\Controllers\Sistema;

use App\Models\Year;
use App\Models\Month;
use App\Models\Order;
use App\Models\Balance;
use App\Models\Product;
use App\Models\Quantify;
use App\Models\DetailOrder;
use App\Models\OrderStatus;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Models\ProgressOrder;
use App\Models\ProductExpiration;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;

class RepeatOrderController extends ApiController
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
            'date.after_or_equal'    => 'La fecha tiene que ser igual o mayor a '.date('d/m/Y').'.',
            'orders_id.exists'    => 'Debe de seleccionar la menos una orden.',
        ];

        $rules = [
            'date' => 'required|date|after_or_equal:'.date('Y-m-d'),
            'orders_id' => 'required|integer|exists:orders,id',
        ];
        
        $this->validate($request, $rules, $messages);

        try {
            DB::beginTransaction();

                $estado_orden = OrderStatus::where('status',OrderStatus::PEDIDO)->first();

                $mes = Month::find(date('n',strtotime($request->date)));
                $anio = Year::where('year',date('Y',strtotime($request->date)))->first();

                $numero_actual = Reservation::where('year',date('Y'))->get()->last();
                if(is_null($numero_actual))
                    $numero_actual = 0;
                else
                    $numero_actual = $numero_actual->correlative;
        
                $reservation = new Reservation();
                $reservation->correlative = $numero_actual + 1; 
                $reservation->year = date('Y'); 
                $reservation->save();

                $order = Order::find($request->orders_id);

                if($order->repeat == 0)
                    $asociado = $order->id;
                else
                    $asociado = $order->repeat;

                $insert_orden = new Order();
                $insert_orden->order = $reservation->correlative.'-'.$reservation->year;
                $insert_orden->title = $order->title;
                $insert_orden->description = $order->description;
                $insert_orden->date = date('Y-m-d',strtotime($request->date));
                $insert_orden->schools_id = $order->schools_id;
                $insert_orden->type_order = $order->type_order;
                $insert_orden->people_id = Auth::user()->people_id;                
                $insert_orden->months_id = $mes->id;             
                $insert_orden->years_id = $anio->id;
                $insert_orden->code = $order->code;
                $insert_orden->repeat = $asociado;
                $insert_orden->save();

                $details = DetailOrder::where('orders_id',$order->id)->get();

                foreach($details as $detail) { 
                    $insert_detalle_orden = new DetailOrder();
                    $insert_detalle_orden->quantity = $detail->quantity;
                    $insert_detalle_orden->sale_price = $detail->sale_price;
                    $insert_detalle_orden->subtotal = $detail->sale_price*$detail->quantity;
                    $insert_detalle_orden->observation = $detail->observation;
                    $insert_detalle_orden->products_id = $detail->products_id;
                    $insert_detalle_orden->orders_id = $insert_orden->id;
                    $insert_detalle_orden->complete = false;
                    $insert_detalle_orden->save();

                    $insert_progreso_orden = new ProgressOrder();
                    $insert_progreso_orden->purchased_amount = 0;
                    $insert_progreso_orden->original_quantity = $insert_detalle_orden->quantity;
                    $insert_progreso_orden->order_statuses_id = $estado_orden->id;
                    $insert_progreso_orden->detail_orders_id = $insert_detalle_orden->id;
                    $insert_progreso_orden->products_id = $insert_detalle_orden->products_id;
                    $insert_progreso_orden->save();

                    $balance = Balance::where([
                        ['code',$insert_orden->code],
                        ['schools_id',$insert_orden->schools_id],
                        ['type_balance',$insert_orden->type_order],
                        ['current',true]
                    ])->first();

                    $product = Product::find($insert_detalle_orden->products_id);
                    
                    $insert_quantify = Quantify::where('products_id',$insert_detalle_orden->products_id)->where('year',$anio->year)->first();


                    if($insert_detalle_orden->quantity > 1){
                        for ($i=0; $i < $insert_detalle_orden->quantity; $i++) { 
                            if($product->stock_temporary > 0){
                                $product->stock_temporary -= 1;
                                
                                if(!$product->persevering)
                                {
                                    $expiration = ProductExpiration::where('products_id',$product->id)->where('expiration',false)->where('current',true)->latest()->orderBy('date', 'asc')->first();
                                    $expiration->used -= 1;
                    
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
                                $expiration->used -= 1;
                
                                if($expiration->used == 0)
                                    $expiration->current = false;
                    
                                $expiration->save();
                            }

                        }else{
                            $insert_quantify->subtraction += 1;
                        }
                    }
                    
                    $insert_quantify->sumary_schools +=  $insert_detalle_orden->quantity;

                    $insert_orden->total += $insert_detalle_orden->subtotal;
                    $balance->subtraction_temporary += $insert_detalle_orden->subtotal;

                    if($balance->balance == $balance->subtraction_temporary)
                        $balance->current = true;

                    if(($balance->subtraction_temporary - $balance->subtraction_temporary) < 0)
                        return $this->errorResponse('El monto del pedido excede al monto disponible en el código '.$order->code, 422);

                    $insert_orden->balances_id = $balance->id;
                    $insert_quantify->save();
                    $product->save();
                    $insert_orden->save();
                    $balance->save();
                }

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Order $repeat_order)
    {
        $balance = Balance::where('code',$repeat_order->code)->where('type_balance',$repeat_order->type_order)->where('current',true)->first();

        if(!is_null($balance))
        {
            if(($balance->subtraction_temporary + $repeat_order->total) > $balance->balance)
                return $this->errorResponse('El código '.$repeat_order->code.', no tiene suficiente crédito para realizar el pedido.',422);


            return $this->showOne($balance);
        }
        else
        {
            return $this->errorResponse('El código '.$repeat_order->code.', no tiene asignado monto para el desembolso.',422);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

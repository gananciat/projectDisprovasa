<?php

namespace App\Http\Controllers\Sistema;

use App\Models\Rol;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;
use App\Models\DetailOrder;
use App\Models\Month;
use App\Models\OrderStatus;
use App\Models\PersonSchool;
use App\Models\ProgressOrder;
use App\Models\Year;
use Illuminate\Support\Facades\DB;

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

            $orders = Order::where('schools_id',$person_school->schools_id)->get();
        } else {
            $orders = Order::all();
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
            'detail_order.products_id.exists'    => 'Debe de seleccionar la menos un producto para la orden.',
            'title.unique'    => 'El título de la orden debe ser único.',
        ];

        $rules = [
            'order' => 'required|integer',
            'title' => 'required|string|max:125|unique:orders,title',
            'description' => 'required|string|max:200',
            'date' => 'required|date',
            'detail_order.quantity.*' => 'required|numeric',
            'detail_order.sale_price.*' => 'required|numeric',
            'detail_order.subtotal.*' => 'required|numeric',
            'detail_order.observation.*' => 'nullable|max:125',
            'detail_order.products_id.*' => 'required|integer|exists:products,id',
        ];
        
        $this->validate($request, $rules, $messages);

        try {
            DB::beginTransaction();

                $estado_orden = OrderStatus::where('status',OrderStatus::PEDIDO)->first();
                $person_school = PersonSchool::where([
                    ['current','=',true],
                    ['people_id','=',Auth::user()->people_id]
                ])->first();

                $mes = Month::find(date('n',strtotime($request->date)));
                $anio = Year::where('year',date('Y',strtotime($request->date)));

                $insert_orden = new Order();
                $insert_orden->order = $request->order;
                $insert_orden->title = $request->title;
                $insert_orden->description = $request->description;
                $insert_orden->date = $request->date;
                $insert_orden->schools_id = 1;
                $insert_orden->people_id = Auth::user()->people_id;                
                $insert_orden->months_id = $mes->id;             
                $insert_orden->years_id = $anio->id;
                $insert_orden->save();

                for ($i=0; $i < count($request->detail_order); $i++) { 
                    $insert_detalle_orden = new DetailOrder();
                    $insert_detalle_orden->quantity = $request->detail_order[$i]['quantity'];
                    $insert_detalle_orden->sale_price = $request->detail_order[$i]['sale_price'];
                    $insert_detalle_orden->subtotal = $request->detail_order[$i]['subtotal'];
                    $insert_detalle_orden->observation = $request->detail_order[$i]['observation'];
                    $insert_detalle_orden->products_id = $request->detail_order[$i]['products_id'];
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
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return $this->showOne($order, 201);
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
            'order' => 'required|integer',
            'title' => 'required|string|max:125|unique:orders,title,'.$order->id,
            'description' => 'required|string|max:200',
            'date' => 'required|date',
        ];
        
        $this->validate($request, $rules, $messages);

        try {
            DB::beginTransaction();

                $person_school = PersonSchool::where([
                    ['current','=',true],
                    ['people_id','=',Auth::user()->people_id]
                ])->first();

                $mes = Month::find(date('n',strtotime($request->date)));
                $anio = Year::where('year',date('Y',strtotime($request->date)));

                $order->order = $request->order;
                $order->title = $request->title;
                $order->description = $request->description;
                $order->date = $request->date;
                $order->schools_id = 1;
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
    public function destroy(Order $order)
    {
        try {

            DB::beginTransaction();

                $detalle_orden = DetailOrder::where([
                    ['orders_id','=',$order->id],
                    ['complete','=',true]
                ])->get();
                
                if(count($detalle_orden) == 0){
                    $detalle_orden = DetailOrder::where('orders_id','=',$order->id)->get();

                    foreach ($detalle_orden as $key => $value) {
                        ProgressOrder::where('detail_orders_id',$value->id)->delete();
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

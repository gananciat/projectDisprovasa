<?php

namespace App\Http\Controllers\Sistema;

use App\Models\Quantify;
use App\Models\DetailOrder;
use App\Models\OrderStatus;
use Illuminate\Http\Request;
use App\Models\ProgressOrder;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

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
            'subtotal' => 'required|numeric',
            'observation' => 'nullable|max:125',
            'products_id' => 'required|integer|exists:products,id',
        ];
        
        $this->validate($request, $rules, $messages);

        try {
            DB::beginTransaction();

                $estado_orden = OrderStatus::where('status',OrderStatus::PEDIDO)->first();

                $insert_detalle_orden = new DetailOrder();
                $insert_detalle_orden->quantity = $request->quantity;
                $insert_detalle_orden->sale_price = $request->sale_price;
                $insert_detalle_orden->subtotal = $request->subtotal;
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

                $exist_quantify = Quantify::where('products_id',$insert_detalle_orden->products_id)->where('year',date('Y'))->firts();

                if(is_null($exist_quantify)) {
                    $insert_quantify = new Quantify();
                    $insert_quantify->sumary_schools = $insert_detalle_orden->quantity;
                } else {
                    $insert_quantify->sumary_schools = $insert_quantify->sumary_schools + $insert_detalle_orden->quantity;
                }

                $insert_quantify->year = date('Y');
                $insert_quantify->products_id = $insert_detalle_orden->products_id;
                $insert_quantify->save();

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
    public function show(DetailOrder $detail_order)
    {
        //
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
        $messages = [
            'products_id.exists'    => 'Debe de seleccionar la menos un producto para la orden.',
        ];

        $rules = [
            'quantity' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'subtotal' => 'required|numeric',
            'observation' => 'nullable|max:125',
            'products_id' => 'required|integer|exists:products,id',
        ];
        
        $this->validate($request, $rules, $messages);

        try {
            DB::beginTransaction();

                $estado_orden = OrderStatus::where('status',OrderStatus::PEDIDO)->first();

                $progress_order = ProgressOrder::where('detail_orders_id',$detail_order->id)->first();
                if($request->quantity >= $progress_order->purchased_amount){
                    
                    $detail_order->quantity = $request->quantity;
                    $detail_order->sale_price = $request->sale_price;
                    $detail_order->subtotal = $request->subtotal;
                    $detail_order->observation = $request->observation;
                    $detail_order->products_id = $request->products_id;
                    $detail_order->complete = false;


                    $progress_order->original_quantity = $detail_order->quantity;
                    if($detail_order->quantity == $progress_order->purchased_amount){
                        $detail_order->complete = true;
                        $estado_orden = OrderStatus::where('status',OrderStatus::COMPLETADO)->first();
                    }

                    $progress_order->order_statuses_id = $estado_orden->id;
                    $progress_order->products_id = $detail_order->products_id;

                    if (!$detail_order->isDirty()) {
                        return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
                    }

                    $exist_quantify = Quantify::where('products_id',$detail_order->products_id)->where('year',date('Y'))->firts();

                    if(is_null($exist_quantify)) {
                        $insert_quantify = new Quantify();
                        $insert_quantify->sumary_schools = $detail_order->quantity;
                    } else {
                        $insert_quantify->sumary_schools = $insert_quantify->sumary_schools + $detail_order->quantity;
                    }
    
                    $insert_quantify->year = date('Y');
                    $insert_quantify->products_id = $detail_order->products_id;
                    $insert_quantify->save();

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

                if($detail_order->complete == false){
                    ProgressOrder::where('detail_orders_id',$detail_order->id)->delete();
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

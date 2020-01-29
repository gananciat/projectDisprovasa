<?php

namespace App\Http\Controllers\Sistema;

use App\Models\Order;
use App\Models\Balance;
use App\Models\Product;
use App\Models\DetailOrder;
use App\Models\OrderStatus;
use Illuminate\Http\Request;
use App\Models\ProgressOrder;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use Illuminate\Database\Eloquent\Builder;

class ProgressOrderController extends ApiController
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
            'detail_orders_id.exists'    => 'Debe de seleccionar la menos una producto de la lista del detalle de la orden.',
        ];

        $rules = [
            'detail_orders_id' => 'required|integer|exists:detail_orders,id',
        ];
        
        $this->validate($request, $rules, $messages);

        try {
            $complete_order = false;
            DB::beginTransaction();

                $detail_order = DetailOrder::find($request->detail_orders_id);
                $detail_order->deliver = true;

                $progress_order = ProgressOrder::where('detail_orders_id', $detail_order->id)->first();

                if($progress_order->purchased_amount != $progress_order->original_quantity)
                {
                    $detail_order->refund = $detail_order->subtotal - ($progress_order->purchased_amount * $detail_order->sale_price);
                    
                    $order = Order::find($detail_order->orders_id);
                    $order->refund += $detail_order->refund;
                    $order->save();

                    $balance = Balance::find($order->balances_id);
                    $balance->subtraction += $detail_order->refund;
                    $balance->current == true;
                    $balance->save();
                }

                $detail_order->save();                

                $order_complete = DetailOrder::where('orders_id',$detail_order->orders_id)->where('deliver',false)->count();
                
                if($order_complete == 0){
                    $order = Order::find($detail_order->orders_id);
                    $order->complete = true;
                    $order->save();
    
                    $complete_order = $order->complete;
                }

                $progress_order = ProgressOrder::where('detail_orders_id',$detail_order->id)->first();
                $progress_order->check = true;
                $progress_order->save();

            DB::commit();

            return $this->successResponse(['complete_order' => $complete_order, 'complete_detail' => $detail_order->deliver, 'code' => 201], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage(),409);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProgressOrder  $progressOrder
     * @return \Illuminate\Http\Response
     */
    public function show($progress_order)
    {
        $orders = Order::select('id','order','title','date','total','schools_id','code')
                        ->with('school:id,name')
                        ->withCount([
                                    'details as detail_complete' => function(Builder $query) {
                                        $query->where('deliver', true);
                                    }, 
                                    'details as detail_total'
                                    ])
                        ->where('type_order',mb_strtoupper($progress_order))
                        ->where('complete',false)
                        ->orderBy('date', 'asc')
                        ->get();

        return $this->showAll($orders);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProgressOrder  $progressOrder
     * @return \Illuminate\Http\Response
     */
    public function edit($progress_order)
    {
        $details = DetailOrder::select('id','complete','products_id','orders_id','deliver')
        ->with(['product:id,name,presentations_id,stock,stock_temporary',
                'product.presentation:id,name',
                'orders:id,order,title,type_order,date,schools_id,code,total',
                'orders.school:id,name,logo',
                'progress:id,original_quantity,purchased_amount,detail_orders_id',
                ])         
        ->where('orders_id',$progress_order)                      
        ->where('deliver',false)
        ->get();

        return $this->showAll($details);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProgressOrder  $progressOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProgressOrder $progress_order)
    {
        $rules = [
            'purchased_amount' => 'required|numeric',
        ];
        
        $this->validate($request, $rules);

        try {
            $complete_order = false;

            DB::beginTransaction();
                $product = Product::find($progress_order->products_id);

                if($request->purchased_amount > $product->stock)
                    return $this->errorResponse('No hay suficiente stock para realizar el proceso.',422);

                $detail_order = DetailOrder::find($progress_order->detail_orders_id);
                $status = OrderStatus::PEDIDO;
                $detail_order->complete = false;
                $detail_order->deliver = false;

                if($request->purchased_amount > $progress_order->purchased_amount){
                    $product->stock = ($product->stock+$progress_order->purchased_amount) - $request->purchased_amount;   
                    $progress_order->purchased_amount = $request->purchased_amount;                 
                }
                elseif ($request->purchased_amount <= $progress_order->purchased_amount && $request->purchased_amount != 0){
                    $product->stock = $product->stock + ($progress_order->purchased_amount-$request->purchased_amount); 
                    $progress_order->purchased_amount = $request->purchased_amount;   
                }else{
                    $product->stock = $product->stock + $progress_order->purchased_amount; 
                    $progress_order->purchased_amount = $request->purchased_amount;                                         
                }

                if($progress_order->purchased_amount == $progress_order->original_quantity){
                    $status = OrderStatus::COMPLETADO;
                    $detail_order->complete = true;
                }
                elseif($progress_order->purchased_amount < $progress_order->original_quantity && $progress_order->purchased_amount != 0){
                    $status = OrderStatus::EN_PROCESO;
                    $detail_order->complete = false;
                }

                $order_status = OrderStatus::where('status',$status)->first();
                $progress_order->order_statuses_id = $order_status->id;

                if($detail_order->complete == true){
                    $detail_order->deliver = true;
                    $progress_order->check = true;
                }

                $product->save();
                $detail_order->save();                
                $progress_order->save();

                $order_complete = DetailOrder::where('orders_id',$detail_order->orders_id)->where('deliver',false)->count();
                
                if($order_complete == 0){

                    $detail_order->refund = $detail_order->subtotal - ($progress_order->purchased_amount * $detail_order->sale_price);
                    $detail_order->save();

                    $order = Order::find($detail_order->orders_id);
                    $order->complete = true;
                    $order->refund += $detail_order->refund;
                    $order->save();

                    $balance = Balance::find($order->balances_id);
                    $balance->subtraction += $detail_order->refund;
                    $balance->current == true;
                    $balance->save();

                    $complete_order = $order->complete;

                }

                $complete_detail = $detail_order->complete;

            DB::commit();

            return $this->successResponse(['complete_order' => $complete_order, 'complete_detail' => $complete_detail, 'code' => 201], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage(),409);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProgressOrder  $progressOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProgressOrder $progressOrder)
    {
        //
    }
}

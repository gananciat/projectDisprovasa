<?php

namespace App\Http\Controllers\Sistema;

use App\Models\Order;
use App\Models\Product;
use App\Models\DetailOrder;
use App\Models\Presentation;
use Illuminate\Http\Request;
use App\Models\ProgressOrder;
use App\Models\CheckDeliveryMan;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;
use App\Models\DeliveryMan;
use Illuminate\Database\Eloquent\Builder;

class CheckDeliveryManController extends ApiController
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
        $orders = Order::select('id','order','title','date','total','schools_id','code','type_order')
                        ->with(['school:id,name'])
                        ->where('complete', true)
                        ->where('on_route', false)
                        ->orderBy('date', 'asc')
                        ->get();

        return $this->showAll($orders);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $orders = Order::select('id','order','title','date','total','schools_id','code','type_order')
                        ->with(['school:id,name','checks_delivery'])
                        ->where('complete', true)
                        ->where('on_route', false)
                        ->orderBy('date', 'asc')
                        ->get();

        return $this->showAll($orders);
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
            'progress_orders_id.exists'    => 'Debe de seleccionar la un producto que se encuentre listo para la entrega.',
            'vehicles_id.exists'    => 'Debe de seleccionar la un vehículo para transportar el producto.'
        ];

        $rules = [
            'progress_orders_id' => 'required|integer|exists:progress_orders,id',
            'vehicles_id' => 'required|integer|exists:vehicles,id',
        ];
        
        $this->validate($request, $rules, $messages);

        try {
            DB::beginTransaction();
                $ready = false;

                $progress = ProgressOrder::find($request->progress_orders_id);
                if($progress->check)
                {
                    $detail = DetailOrder::find($progress->detail_orders_id);

                    $insert_check = new CheckDeliveryMan();
                    $insert_check->check = false;
                    $insert_check->orders_id = $detail->orders_id;
                    $insert_check->detail_orders_id = $detail->id;
                    $insert_check->progress_orders_id = $progress->id;
                    $insert_check->delivery_mans_id = DeliveryMan::where('vehicles_id',$request->vehicles_id)->where('people_id',Auth::user()->people_id)->first()->id;
                    $insert_check->vehicles_id = $request->vehicles_id;
                    $insert_check->people_id = DeliveryMan::where('vehicles_id',$request->vehicles_id)->where('people_id',Auth::user()->people_id)->first()->people_id;
                    $insert_check->save();

                    $detail->on_route = true;
                    $detail->save();

                    $verify_detail = DetailOrder::where('orders_id', $detail->orders_id)->count();
                    $verify_check = CheckDeliveryMan::where('orders_id', $detail->orders_id)->count();

                    if($verify_detail == $verify_check)
                    {
                        $order = Order::find($detail->orders_id);
                        $order->on_route = true;
                        $order->save();
                        $ready = true;
                    }
                }
                else
                {
                    return $this->errorResponse('El producto aún se encuentra en progreso de asignación.', 422);
                }

            DB::commit();

            return $this->successResponse(['ready' => $ready, 'vehicle' => $insert_check->vehicles_id, 'code' => 201], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(),403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CheckDeliveryMan  $checkDeliveryMan
     * @return \Illuminate\Http\Response
     */
    public function show($check_delivery)
    {
        $details = DetailOrder::select('id','deliver','complete','orders_id','on_route')  
                                ->with(['orders.school'])    
                                ->addSelect(['original' => ProgressOrder::select('original_quantity')
                                    ->whereColumn([['detail_orders_id', 'detail_orders.id']])
                                ])         
                                ->addSelect(['progress_orders_id' => ProgressOrder::select('id')
                                    ->whereColumn([['detail_orders_id', 'detail_orders.id']])
                                ])         
                                ->addSelect(['asign' => ProgressOrder::select('purchased_amount')
                                    ->whereColumn([['detail_orders_id', 'detail_orders.id']])
                                ])          
                                ->addSelect(['product' => Product::select('name')
                                    ->whereColumn([['id', 'detail_orders.products_id']])
                                ])           
                                ->addSelect(['presentations_id' => Product::select('presentations_id')
                                    ->whereColumn([['id', 'detail_orders.products_id']])
                                ])         
                                ->addSelect(['marca' => Presentation::select('name')
                                    ->whereColumn([['id', 'presentations_id']])
                                ])          
                                ->addSelect(['check' => CheckDeliveryMan::select('id')
                                    ->whereColumn([['detail_orders_id', 'detail_orders.id']])
                                ])    
                                ->where('orders_id',$check_delivery)                     
                                ->get();

        return $this->showAll($details);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CheckDeliveryMan  $checkDeliveryMan
     * @return \Illuminate\Http\Response
     */
    public function edit($check_delivery)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CheckDeliveryMan  $checkDeliveryMan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CheckDeliveryMan $checkDeliveryMan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CheckDeliveryMan  $checkDeliveryMan
     * @return \Illuminate\Http\Response
     */
    public function destroy(CheckDeliveryMan $check_delivery)
    {
        try {
            DB::beginTransaction();
                $detail = DetailOrder::find($check_delivery->detail_orders_id);
                $detail->on_route = false;
                $detail->save();

                $order = Order::find($detail->orders_id);
                $order->on_route = false;
                $order->save();

                $check_delivery->delete();
            DB::commit();
            return $this->showOne($check_delivery, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(),403);
        }        
    }
}

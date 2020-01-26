<?php

namespace App\Http\Controllers\Sistema;

use App\Models\DetailOrder;
use Illuminate\Http\Request;
use App\Models\ProgressOrder;
use App\Models\CheckDeliveryMan;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Query\Builder;
use App\Http\Controllers\ApiController;
use App\Models\Presentation;
use App\Models\Product;

class CheckDeliveryManController extends ApiController
{
    public function __construct()
    {
        //parent::__construct();
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
        try {
            $progress = ProgressOrder::find($request->progress_orders_id);
            if($progress->check)
            {
                $detail = DetailOrder::find($progress->detail_orders_id);

                $insert_check = new CheckDeliveryMan();
                $insert_check->check = true;
                $insert_check->orders_id = $detail->orders_id;
                $insert_check->detail_orders_id = $detail->id;
                $insert_check->progress_orders_id = $progress->id;
                $insert_check->delivery_mans_id = $request->delivery_mans_id;
                $insert_check->vehicles_id = $request->vehicles_id;
                $insert_check->people_id = Auth::user()->people_id;
                $insert_check->save();

                return $this->showOne($insert_check, 201);
            }
            else
            {
                return $this->errorResponse('El producto aún se encuentra en progreso de asignación.', 422);
            }
        } catch (\Exception $e) {
            //throw $th;
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
        $details = DetailOrder::select('id','deliver')      
                                ->addSelect(['original' => ProgressOrder::select('original_quantity')
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
    public function edit(CheckDeliveryMan $checkDeliveryMan)
    {
        //
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
        $check_delivery->delete();
        return $this->showOne($check_delivery, 201);
    }
}

<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\DetailOrder;
use App\Models\Order;
use App\Models\ProgressOrder;
use Illuminate\Http\Request;

class ProgressOrderController extends ApiController
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProgressOrder  $progressOrder
     * @return \Illuminate\Http\Response
     */
    public function show($progress_order)
    {
        if(!is_null($progress_order)) {
            $orders = Order::where('schools_id',$progress_order)->where('complete',false)->get();
            $ids = array();
    
            foreach ($orders as $value) {
                array_push($ids,$value->id);
            }
            
            $details = DetailOrder::select('id','complete','products_id','orders_id')
                                    ->with(['product:id,name,presentations_id',
                                            'product.presentation:id,name',
                                            'orders:id,order,title,type_order,date,schools_id',
                                            'orders.school:id,name,code_primary,code_high_school',
                                            'progress:id,original_quantity,purchased_amount,detail_orders_id',
                                            ])         
                                    ->whereIn('orders_id',$ids)                      
                                    ->where('complete',false)->get();
    

        }
        else {
            $details = DetailOrder::select('id','complete','products_id','orders_id')
                                    ->with(['product:id,name,presentations_id',
                                            'product.presentation:id,name',
                                            'orders:id,order,title,type_order,date,schools_id',
                                            'orders.school:id,name,code_primary,code_high_school',
                                            'progress:id,original_quantity,purchased_amount,detail_orders_id',
                                            ])                              
                                    ->where('complete',false)->get();
        }

        return $this->showAll($details);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProgressOrder  $progressOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(ProgressOrder $progressOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProgressOrder  $progressOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProgressOrder $progressOrder)
    {
        //
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

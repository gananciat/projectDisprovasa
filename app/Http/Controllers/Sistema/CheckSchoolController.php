<?php

namespace App\Http\Controllers\Sistema;

use App\Models\Order;
use App\Models\CheckSchool;
use App\Models\DetailOrder;
use App\Models\PersonSchool;
use Illuminate\Http\Request;
use App\Models\ProgressOrder;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;
use App\Models\CheckDeliveryMan;

class CheckSchoolController extends ApiController
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
        $person_school = PersonSchool::where([
            ['current',true],
            ['people_id',Auth::user()->people_id]
        ])->first();

        $orders = Order::select('id','order','title','code','type_order','date')
                        ->with([
                                'details:id,orders_id,products_id',
                                'details.product:id,name,presentations_id',
                                'details.product.presentation:id,name',
                                'details.progress:id,detail_orders_id,purchased_amount,original_quantity',
                                'details.progress.check:id,progress_orders_id,people_id,vehicles_id,check',
                                'details.progress.check.person:id,name_one,name_two,last_name_one,last_name_two',
                                'details.progress.check.vehicle:id,placa,license_plates_id,vehicle_models_id',
                                'details.progress.check.vehicle.plate:id,type',
                                'details.progress.check.vehicle.model:id,brand_model'
                               ])
                        ->where('schools_id',$person_school->schools_id)
                        ->where('on_route',true)
                        ->where('complete',true)
                        ->orderBy('date','desc')
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
            'progress_orders_id.exists'    => 'Debe de seleccionar la un producto que se encuentre listo para la entrega.'
        ];

        $rules = [
            'progress_orders_id' => 'required|integer|exists:progress_orders,id'
        ];
        
        $this->validate($request, $rules, $messages);

        try {
            DB::beginTransaction();
                $ready = false;

                $person_school = PersonSchool::where([
                    ['current',true],
                    ['people_id',Auth::user()->people_id]
                ])->first();

                $check_delivery = CheckDeliveryMan::where('progress_orders_id',$request->progress_orders_id)->first();
                if($check_delivery->check)
                {
                    $detail = DetailOrder::find($check_delivery->detail_orders_id);

                    if(!is_null(CheckSchool::where('check_delivery_man_id',$check_delivery->id)->first()))
                        return $this->errorResponse('El producto ya fue aceptado.', 422);

                    $insert_check = new CheckSchool();
                    $insert_check->check = false;
                    $insert_check->orders_id = $detail->orders_id;
                    $insert_check->detail_orders_id = $detail->id;
                    $insert_check->check_delivery_man_id = $check_delivery->id;
                    $insert_check->schools_id = $person_school->schools_id;
                    $insert_check->people_id = $person_school->people_id;
                    $insert_check->save();

                    $detail->aware = true;
                    $detail->save();

                    $verify_detail = DetailOrder::where('orders_id', $detail->orders_id)->count();
                    $verify_check = CheckSchool::where('orders_id', $detail->orders_id)->count();

                    if($verify_detail == $verify_check)
                    {
                        $order = Order::find($detail->orders_id);
                        $order->aware = true;
                        $order->save();
                        $ready = true;
                    }
                }
                else
                {
                    return $this->errorResponse('El producto aún se encuentra en ruta de entrega.', 422);
                }

            DB::commit();

            return $this->successResponse(['ready' => $ready, 'code' => 201], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(),403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CheckSchool  $checkSchool
     * @return \Illuminate\Http\Response
     */
    public function show($check_school)
    {
        $person_school = PersonSchool::where([
            ['current',true],
            ['people_id',Auth::user()->people_id]
        ])->first();

        $orders = Order::select('id','order','title','code','type_order','date','refund','total','aware')
                        ->with([
                                'details:id,orders_id,products_id,sale_price,subtotal,refund,aware',
                                'details.product:id,name,presentations_id',
                                'details.product.presentation:id,name',
                                'details.progress:id,detail_orders_id,purchased_amount,original_quantity',
                                'details.progress.check:id,progress_orders_id,people_id,vehicles_id,check',
                                'details.progress.check.person:id,name_one,name_two,last_name_one,last_name_two',
                                'details.progress.check.vehicle:id,placa,license_plates_id,vehicle_models_id',
                                'details.progress.check.vehicle.plate:id,type',
                                'details.progress.check.vehicle.model:id,brand_model'
                               ])
                        ->where('schools_id',$person_school->schools_id)
                        ->where('on_route',true)
                        ->where('complete',true)
                        ->where('type_order',mb_strtoupper($check_school))
                        ->orderBy('date','asc')
                        ->get();

        return $this->showAll($orders);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CheckSchool  $checkSchool
     * @return \Illuminate\Http\Response
     */
    public function edit(CheckSchool $checkSchool)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CheckSchool  $checkSchool
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CheckSchool $checkSchool)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CheckSchool  $checkSchool
     * @return \Illuminate\Http\Response
     */
    public function destroy($check_school)
    {
        try {
            DB::beginTransaction();
                $progress = CheckDeliveryMan::where('progress_orders_id',$check_school)->first();

                $check_delete = CheckSchool::where('check_delivery_man_id',$progress->id)->first();

                $detail = DetailOrder::find($check_delete->detail_orders_id);
                $detail->aware = false;
                $detail->save();

                $order = Order::find($detail->orders_id);
                $order->aware = false;
                $order->save();

                $check_delete->delete();
            DB::commit();

            return $this->showOne($check_delete, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(),403);
        } 
    }
}

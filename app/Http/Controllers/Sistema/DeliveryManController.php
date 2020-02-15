<?php

namespace App\Http\Controllers\Sistema;

use App\Models\DeliveryMan;
use App\Models\LicensePlate;
use App\Models\VehicleModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;

class DeliveryManController extends ApiController
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
        $deliverys = DeliveryMan::with('vehicle.plate','vehicle.model')
                                ->where('people_id', Auth::user()->people_id)
                                ->get();

        return $this->showAll($deliverys);
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
            'people_id.exists'    => 'Debe de seleccionar la menos un empleado.',
            'type_license_id.exists'    => 'Debe de seleccionar la menos un tipo de licencia.',
            'vehicles_id.exists'    => 'Debe de seleccionar la menos un vehículo.',
        ];


        $rules = [
            'people_id' => 'required|integer|exists:people,id',
            'type_license_id' => 'required|integer|exists:type_license,id',
            'vehicles_id' => 'required|integer|exists:vehicles,id',
        ];
        
        $this->validate($request, $rules, $messages);

        $insert = new DeliveryMan();
        $insert->people_id = $request->people_id;
        $insert->type_license_id = $request->type_license_id;
        $insert->vehicles_id = $request->vehicles_id;
        $insert->save();

        return $this->showOne($insert,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DeliveryMan  $deliveryMan
     * @return \Illuminate\Http\Response
     */
    public function show($delivery_man)
    {
        $deliverys = DeliveryMan::with('vehicle.plate','vehicle.model','license')
                                ->where('people_id', $delivery_man)
                                ->orderBy('id','desc')
                                ->get();

        return $this->showAll($deliverys);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DeliveryMan  $deliveryMan
     * @return \Illuminate\Http\Response
     */
    public function edit(DeliveryMan $deliveryMan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DeliveryMan  $deliveryMan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DeliveryMan $delivery_man)
    {
        $messages = [
            'type_license_id.exists'    => 'Debe de seleccionar la menos un tipo de licencia.',
            'vehicles_id.exists'    => 'Debe de seleccionar la menos un vehículo.',
        ];


        $rules = [
            'type_license_id' => 'required|integer|exists:type_license,id',
            'vehicles_id' => 'required|integer|exists:vehicles,id',
        ];

        $this->validate($request, $rules, $messages);

        $delivery_man->type_license_id = $request->type_license_id;
        $delivery_man->vehicles_id = $request->vehicles_id;

        if (!$delivery_man->isDirty()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $delivery_man->save();

        return $this->showOne($delivery_man,201);        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DeliveryMan  $deliveryMan
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeliveryMan $delivery_man)
    {
        $delivery_man->delete();
        return $this->showOne($delivery_man,201); 
    }
}

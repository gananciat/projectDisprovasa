<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\LicensePlate;
use App\Models\Vehicle;
use App\Models\VehicleModel;
use Illuminate\Http\Request;

class VehicleController extends ApiController
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
        $vehicle = Vehicle::addSelect(['type_plate' => LicensePlate::select('type')
                                            ->whereColumn([
                                                            ['id', 'vehicles.license_plates_id'],
                                                        ])
                                        ])
                            ->addSelect(['brand_model' => VehicleModel::select('brand_model')
                                            ->whereColumn([
                                                            ['id', 'vehicles.vehicle_models_id'],
                                                        ])
                                        ])->get();

        return $this->showAll($vehicle);
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
            'placa.unique'  => 'Ya existe un número de placa reistrado con está información',
            'vin.unique'  => 'Ya existe un número de vin reistrado con está información',
            'chasis.unique'  => 'Ya existe un número de chasis reistrado con está información',
            'license_plates_id.exists'    => 'Debe de seleccionar la menos un tipo de placa.',
            'vehicle_models_id.exists'    => 'Debe de seleccionar la menos una marca y modelo de vehículo.',
            'anio.between' => 'El año debe de estar en el rango de :min hasta :max.',
            'motor.between' => 'El tamaño del motor debe de estar en el rango de :min hasta :max.',
        ];


        $rules = [
            'placa' => 'required|string|max:6|unique:vehicles,placa',
            'color' => 'required|string|max:25',
            'anio' => 'required|integer|max:4|between:1890,'.date("Y",strtotime(date('Y-m-d')."+ 1 year")),
            'vin' => 'required|string|max:16|unique:vehicles,vin',
            'chasis' => 'required|string|max:16|unique:vehicles,chasis',
            'motor' => 'required|integer|between:1000,5000',
            'license_plates_id' => 'required|integer|exists:license_plates,id',
            'vehicle_models_id' => 'required|integer|exists:vehicle_models,id',
        ];
        
        $this->validate($request, $rules, $messages);

        $insert = new Vehicle();
        $insert->placa = $request->placa;
        $insert->color = $request->color;
        $insert->anio = $request->anio;
        $insert->vin = $request->vin;
        $insert->chasis = $request->chasis;
        $insert->motor = $request->motor;
        $insert->license_plates_id = $request->license_plates_id;
        $insert->vehicle_models_id = $request->vehicle_models_id;
        $insert->save();

        return $this->showOne($insert,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function show(Vehicle $vehicle)
    {
        $vehicle = Vehicle::with('plate:id,name','model:id,brand_model')->where('id',$vehicle->id)->get();
        return $this->showAll($vehicle);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function edit(Vehicle $vehicle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $messages = [
            'placa.unique'  => 'Ya existe un número de placa reistrado con está información',
            'vin.unique'  => 'Ya existe un número de vin reistrado con está información',
            'chasis.unique'  => 'Ya existe un número de chasis reistrado con está información',
            'license_plates_id.exists'    => 'Debe de seleccionar la menos un tipo de placa.',
            'vehicle_models_id.exists'    => 'Debe de seleccionar la menos una marca y modelo de vehículo.',
            'anio.between' => 'El año debe de estar en el rango de :min hasta :max.',
            'motor.between' => 'El tamaño del motor debe de estar en el rango de :min hasta :max.',
        ];


        $rules = [
            'placa' => 'required|string|max:6|unique:vehicles,placa,'.$vehicle->id,
            'color' => 'required|string|max:25',
            'anio' => 'required|integer|max:4|between:1890,'.date("Y",strtotime(date('Y-m-d')."+ 1 year")),
            'vin' => 'required|string|max:16|unique:vehicles,vin,'.$vehicle->id,
            'chasis' => 'required|string|max:16|unique:vehicles,chasis,'.$vehicle->id,
            'motor' => 'required|integer|between:1000,5000',
            'license_plates_id' => 'required|integer|exists:license_plates,id',
            'vehicle_models_id' => 'required|integer|exists:vehicle_models,id',
        ];
        
        $this->validate($request, $rules, $messages);

        $vehicle->placa = $request->placa;
        $vehicle->color = $request->color;
        $vehicle->anio = $request->anio;
        $vehicle->vin = $request->vin;
        $vehicle->chasis = $request->chasis;
        $vehicle->motor = $request->motor;
        $vehicle->license_plates_id = $request->license_plates_id;
        $vehicle->vehicle_models_id = $request->vehicle_models_id;
        $vehicle->save();

        return $this->showOne($vehicle,201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return $this->showOne($vehicle);
    }
}

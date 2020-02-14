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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DeliveryMan  $deliveryMan
     * @return \Illuminate\Http\Response
     */
    public function show($delivery_man)
    {
        $deliverys = DeliveryMan::with('vehicle.plate','vehicle.model')
                                ->where('people_id', $delivery_man)
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
    public function update(Request $request, DeliveryMan $deliveryMan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DeliveryMan  $deliveryMan
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeliveryMan $deliveryMan)
    {
        //
    }
}

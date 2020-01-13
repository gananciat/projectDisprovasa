<?php

namespace App\Http\Controllers\Sistema;

use App\Models\Reservation;
use App\Models\PersonSchool;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;
use App\Models\Balance;

class ReservationController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
    
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
        if(Auth::user()->current_school == true){
            $numero_actual = Reservation::where('year',date('Y'))->get()->last();
            if(is_null($numero_actual))
                $numero_actual = 0;
            else
                $numero_actual = $numero_actual->correlative;
    
            $insert = new Reservation();
            $insert->correlative = $numero_actual + 1; 
            $insert->year = date('Y'); 
            $insert->save();
    
            return $this->showOne($insert,201);
        } else {
            return $this->errorResponse('Error al obtener el número de pedido', 409);
        }        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function show($reservation)
    {
        $balance = Balance::with('disbursement')->where('code',$reservation)->where('current',true)->get();

        if(!is_null($balance))
            return $this->showAll($balance);
        else
            return $this->errorResponse('El código '.$reservation.', no tiene asignado monto para el desembolso.',422);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function edit(Reservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reservation $reservation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reservation $reservation)
    {
        //
    }

    public function money($code, $type_order)
    {

        switch ($type_order) {
            case 'alimentacion':
                $propierty = Balance::ALIMENTACION;
                break;
            case 'gratuidad':
                $propierty = Balance::GRATUIDAD;
                break;
            case 'utiles':
                $propierty = Balance::UTILES;
                break;
        }

        $balance = Balance::with('disbursement')->where('code',$code)->where('type_balance',$propierty)->where('current',true)->get();

        if(!is_null($balance))
            return $this->showAll($balance);
        else
            return $this->errorResponse('El código '.$reservation.', no tiene asignado monto para el desembolso.',422);
    }
}

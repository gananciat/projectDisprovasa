<?php

namespace App\Http\Controllers\Sistema;

use App\Models\PhonePerson;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class PhonePersonController extends ApiController
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
            'people_id.exists'    => 'Debe de seleccionar la menos una persona.',
            'number.digits_between'    => 'El número de teléfono de la persona debe de tener 8 digitos.',
            'companies_id.exists'    => 'Debe de seleccionar la menos una compania para el número de la persona.',        
        ];

        $rules = [
            'people_id' => 'required|integer|exists:people,id',
            'number' => 'required|digits_between:8,8',
            'companies_id' => 'required|integer|exists:companies,id',
        ];
        
        $this->validate($request, $rules, $messages);

        $data = $request->all();
        $insert = PhonePerson::create($data);
        return $this->showOne($insert,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PhonePerson  $phonePerson
     * @return \Illuminate\Http\Response
     */
    public function show(PhonePerson $phone_person)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PhonePerson  $phonePerson
     * @return \Illuminate\Http\Response
     */
    public function edit(PhonePerson $phone_person)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PhonePerson  $phonePerson
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PhonePerson $phone_person)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PhonePerson  $phonePerson
     * @return \Illuminate\Http\Response
     */
    public function destroy(PhonePerson $phone_person)
    {
        $phone_person->delete();
        return $this->showOne($phone_person,201);
    }
}

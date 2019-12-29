<?php

namespace App\Http\Controllers\Sistema;

use App\Models\PhoneSchool;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class PhoneSchoolController extends ApiController
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
            'schools_id.exists'    => 'Debe de seleccionar la menos una escuela.',
            'number.digits_between'    => 'El número de teléfono de la escuela debe de tener 8 digitos.',
            'companies_id.exists'    => 'Debe de seleccionar la menos una compania para el número de la escuela.',        
        ];

        $rules = [
            'schools_id' => 'required|integer|exists:schools,id',
            'number' => 'required|digits_between:8,8',
            'companies_id' => 'required|integer|exists:companies,id',
        ];
        
        $this->validate($request, $rules, $messages);

        $data = $request->all();
        $insert = PhoneSchool::create($data);
        return $this->showOne($insert,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PhoneSchool  $phoneSchool
     * @return \Illuminate\Http\Response
     */
    public function show(PhoneSchool $person_school)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PhoneSchool  $phoneSchool
     * @return \Illuminate\Http\Response
     */
    public function edit(PhoneSchool $person_school)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PhoneSchool  $phoneSchool
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PhoneSchool $person_school)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PhoneSchool  $phoneSchool
     * @return \Illuminate\Http\Response
     */
    public function destroy(PhoneSchool $person_school)
    {
        $person_school->delete();
        return $this->showOne($person_school,201);
    }
}

<?php

namespace App\Http\Controllers\Sistema;

use App\User;
use App\Models\Rol;
use App\Models\Person;
use App\Models\School;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\SchoolPresident;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ApiController;

class SchoolPresidentController extends ApiController
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
            'schools_id.exists'    => 'Debe de seleccionar la menos una escuela.',
        ];

        $rules = [
            'schools_id' => 'required|integer|exists:schools,id',
            'people_id' => 'required|integer|exists:people,id',
        ];
        
        $this->validate($request, $rules, $messages);

        try {
            DB::beginTransaction();

                $asignar_persona_escuela = new SchoolPresident();
                $asignar_persona_escuela->current = true;
                $asignar_persona_escuela->schools_id = $request->schools_id;
                $asignar_persona_escuela->people_id = $request->people_id;
                $asignar_persona_escuela->save();              

            DB::commit();

            return $this->showOne($asignar_persona_escuela,201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(),403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SchoolPresident  $schoolPresident
     * @return \Illuminate\Http\Response
     */
    public function show(School $school_president)
    {
        $presidents = School::with('presidents.person.municipality.departament')->where('id',$school_president->id)->get();
        return $this->showAll($presidents);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SchoolPresident  $schoolPresident
     * @return \Illuminate\Http\Response
     */
    public function edit(SchoolPresident $school_president)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SchoolPresident  $schoolPresident
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SchoolPresident $school_president)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SchoolPresident  $schoolPresident
     * @return \Illuminate\Http\Response
     */
    public function destroy(SchoolPresident $school_president)
    {
        try {
            DB::beginTransaction();

                if($school_president->current != false)
                    $school_president->current = false;
                else
                    $school_president->current = true;
                    
                $school_president->save();

            DB::commit();

            return $this->showOne($school_president,201);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(),403);
        }
    }
}

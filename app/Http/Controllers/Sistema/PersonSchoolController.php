<?php

namespace App\Http\Controllers\Sistema;

use App\User;
use App\Models\Rol;
use App\Models\Person;
use Illuminate\Support\Str;
use App\Models\PersonSchool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ApiController;

class PersonSchoolController extends ApiController
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
            'cui'    => 'El número de DPI debe de tener entre :min y :max digitos.',
            'municipalities_id_people.exists'    => 'Debe de seleccionar la menos un municipio para la persona.',
            'type_person.starts_with'    => 'Solo se aceptan los valores de '.PersonSchool::DIRECTOR.','.PersonSchool::PROFESOR.','.PersonSchool::OTRO
        ];

        $rules = [
            'schools_id' => 'required|integer|exists:schools,id',
            'cui' => 'required|digits_between:13,15|unique:people,cui',
            'name_one' => 'required|string|max:25',
            'name_two' => 'nullable|string|max:50',
            'last_name_one' => 'required|string|max:25',
            'last_name_two' => 'nullable|string|max:50',
            'direction_people' => 'required|string|max:200',
            'email' => 'required|email|max:125|unique:people,email',
            'municipalities_id_people' => 'required|integer|exists:municipalities,id',

            'type_person' => 'required|starts_with:'.PersonSchool::DIRECTOR.','.PersonSchool::PROFESOR.','.PersonSchool::OTRO
        ];
        
        $this->validate($request, $rules, $messages);

        try {
            DB::beginTransaction();
                $data = $request->all();

                $insert = Person::where('cui',$data->cui)->first();

                if(is_null($insert)) {
                    $insert = new Person();
                } 

                $insert->cui = $data->cui;
                $insert->name_one = $data->name_one;
                $insert->name_two = $data->name_two;
                $insert->last_name_one = $data->last_name_one;
                $insert->last_name_two = $data->last_name_two;
                $insert->direction = $data->direction_people;
                $insert->email = $data->email;
                $insert->municipalities_id = $data->municipalities_id_people;
                $insert->save();

                $existe_asignacion = PersonSchool::where('people_id',$insert->id)->where('current',true)->get();

                foreach ($existe_asignacion as $key => $value) {
                    $value->current = false;
                    $value->save();
                }

                $asignar_persona_escuela = new PersonSchool();
                $asignar_persona_escuela->type_person = $data->type_person;
                $asignar_persona_escuela->current = true;
                $asignar_persona_escuela->schools_id = $data->schools_id;
                $asignar_persona_escuela->people_id = $insert->id;
                $asignar_persona_escuela->save();

                $insert_user = User::where('people_id',$insert->id)->first();

                if(is_null($insert_user)) {
                    $insert_user = new User();
                }

                $insert_user->email = $insert->email;
                $insert_user->password = Hash::make($this->generarPassword(16));
                $insert_user->remember_token = Str::random(20);
                $insert_user->verified = User::USUARIO_NO_VERIFICADO;
                $insert_user->verification_token = User::generarVerificationToken();
                $insert_user->admin = User::USUARIO_REGULAR;
                $insert_user->people_id = $insert->id;
                $insert_user->rols_id = Rol::select('id')->where('name',$asignar_persona_escuela->type_person)->first();
                $insert_user->save();                

            DB::commit();

            return $this->showOne($insert,201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(),403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PersonSchool  $personSchool
     * @return \Illuminate\Http\Response
     */
    public function show(PersonSchool $person_school)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PersonSchool  $personSchool
     * @return \Illuminate\Http\Response
     */
    public function edit(PersonSchool $person_school)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PersonSchool  $personSchool
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PersonSchool $person_school)
    {
        $messages = [
            'schools_id.exists'    => 'Debe de seleccionar la menos una escuela.',
            'cui'    => 'El número de DPI debe de tener entre :min y :max digitos.',
            'municipalities_id_people.exists'    => 'Debe de seleccionar la menos un municipio para la persona.',
            'type_person.starts_with'    => 'Solo se aceptan los valores de '.PersonSchool::DIRECTOR.','.PersonSchool::PROFESOR.','.PersonSchool::OTRO
        ];

        $rules = [
            'cui' => 'required|digits_between:13,15|unique:people,cui,'.$person_school->people_id,
            'name_one' => 'required|string|max:25',
            'name_two' => 'nullable|string|max:50',
            'last_name_one' => 'required|string|max:25',
            'last_name_two' => 'nullable|string|max:50',
            'direction_people' => 'required|string|max:200',
            'email' => 'required|email|max:125|unique:people,email,'.$person_school->people_id,
            'municipalities_id_people' => 'required|integer|exists:municipalities,id',

            'type_person' => 'required|starts_with:'.PersonSchool::DIRECTOR.','.PersonSchool::PROFESOR.','.PersonSchool::OTRO
        ];
        
        $this->validate($request, $rules, $messages);

        try {
            DB::beginTransaction();
                $data = $request->all();

                $update = Person::find($person_school->people_id);
                $update->cui = $data->cui;
                $update->name_one = $data->name_one;
                $update->name_two = $data->name_two;
                $update->last_name_one = $data->last_name_one;
                $update->last_name_two = $data->last_name_two;
                $update->direction = $data->direction_people;
                $update->email = $data->email;
                $update->municipalities_id = $data->municipalities_id_people;
                $update->save();

                $person_school->type_person = $data->type_person;
                $person_school->current = true;
                $person_school->people_id = $update->id;
                $person_school->save();

                $update_user = User::where('people_id',$person_school->people_id)->first();
                if($update_user->email != $update->email)
                {
                    $update_user->email = $update->email;
                    $update_user->password = Hash::make($this->generarPassword(16));
                    $update_user->remember_token = Str::random(20);
                    $update_user->verified = User::USUARIO_NO_VERIFICADO;
                    $update_user->verification_token = User::generarVerificationToken();
                    $update_user->admin = User::USUARIO_REGULAR;
                    $update_user->rols_id = Rol::select('id')->where('name',$person_school->type_person)->first();
                    $update_user->save();    
                }            

            DB::commit();

            return $this->showOne($update,201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(),403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PersonSchool  $personSchool
     * @return \Illuminate\Http\Response
     */
    public function destroy(PersonSchool $person_school)
    {
        try {
            DB::beginTransaction();

                $person_school->current = false;
                $person_school->save();

                $insert_user = User::where('people_id',$person_school->people_id)->first();

                $insert_user->password = Hash::make($this->generarPassword(16));
                $insert_user->remember_token = Str::random(20);
                $insert_user->verified = User::USUARIO_NO_VERIFICADO;
                $insert_user->verification_token = User::generarVerificationToken();
                $insert_user->admin = User::USUARIO_REGULAR;
                $insert_user->save();    

            DB::commit();

            return $this->showOne($person_school,201);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(),403);
        }
    }
}

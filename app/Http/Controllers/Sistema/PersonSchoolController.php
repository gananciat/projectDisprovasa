<?php

namespace App\Http\Controllers\Sistema;

use App\User;
use App\Models\Rol;
use App\Models\Person;
use App\Mail\WelcomeUser;
use Illuminate\Support\Str;
use App\Models\PersonSchool;
use Illuminate\Http\Request;
use App\Models\SchoolPresident;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
            'type_person.starts_with'    => 'Solo se aceptan los valores de '.PersonSchool::DIRECTOR.','.PersonSchool::PROFESOR.','.PersonSchool::OTRO.','.PersonSchool::PRESIDENTE
        ];

        $rules = [
            'schools_id' => 'required|integer|exists:schools,id',
            'cui' => 'required|digits_between:13,15',
            'name_one' => 'required|string|max:25',
            'name_two' => 'nullable|string|max:50',
            'last_name_one' => 'required|string|max:25',
            'last_name_two' => 'nullable|string|max:50',
            'direction_people' => 'required|string|max:200',
            'email' => 'required|email|max:125|unique:people,email',
            'municipalities_id_people' => 'required|integer|exists:municipalities,id',

            'type_person' => 'required|starts_with:'.PersonSchool::DIRECTOR.','.PersonSchool::PROFESOR.','.PersonSchool::OTRO.','.PersonSchool::PRESIDENTE
        ];
        
        $this->validate($request, $rules, $messages);

        try {
            DB::beginTransaction();
 
                $insert = Person::where('cui',$request->cui)->first();

                if(is_null($insert)) {
                    $insert = new Person();
                } 

                $insert->cui = $request->cui;
                $insert->name_one = $request->name_one;
                $insert->name_two = $request->name_two;
                $insert->last_name_one = $request->last_name_one;
                $insert->last_name_two = $request->last_name_two;
                $insert->direction = $request->direction_people;
                $insert->email = $request->email;
                $insert->municipalities_id = $request->municipalities_id_people;
                $insert->save();

                $existe_asignacion = PersonSchool::where('people_id',$insert->id)->where('current',true)->get();

                foreach ($existe_asignacion as $key => $value) {
                    $value->current = false;
                    $value->save();
                }

                $asignar_persona_escuela = new PersonSchool();
                $asignar_persona_escuela->type_person = $request->type_person;
                $asignar_persona_escuela->current = true;
                $asignar_persona_escuela->schools_id = $request->schools_id;
                $asignar_persona_escuela->people_id = $insert->id;
                $asignar_persona_escuela->save();

                $insert_user = User::where('people_id',$insert->id)->first();

                if(is_null($insert_user)) {
                    $insert_user = new User();
                }

                $rol = Rol::select('id')->where('name',$asignar_persona_escuela->type_person)->first();
                $password = $this->generarPassword(16);
                $insert_user->email = $insert->email;
                $insert_user->password = Hash::make($password);
                $insert_user->remember_token = Str::random(20);
                $insert_user->verified = User::USUARIO_NO_VERIFICADO;
                $insert_user->verification_token = User::generarVerificationToken();
                $insert_user->admin = User::USUARIO_REGULAR;
                $insert_user->people_id = $insert->id;
                $insert_user->rols_id = $rol->id;
                $insert_user->save();                

                if(Rol::ROL_PRESIDENTE == $asignar_persona_escuela->type_person){
                    $buscar = SchoolPresident::where('people_id', $asignar_persona_escuela->people_id)->get();

                    foreach ($buscar as $key => $value) {
                        $value->current = false;
                        $value->save(); 
                    }

                    $insert = new SchoolPresident();
                    $insert->current = true;
                    $insert->schools_id = $asignar_persona_escuela->schools_id;
                    $insert->people_id = $asignar_persona_escuela->people_id;
                    $insert->save(); 
                }

            DB::commit();

            Mail::to($insert_user->email)->send(new WelcomeUser($insert_user, $password));
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
            'cui'    => 'El número de DPI debe de tener entre :min y :max digitos.',
            'municipalities_id_people.exists'    => 'Debe de seleccionar la menos un municipio para la persona.',
            'type_person.starts_with'    => 'Solo se aceptan los valores de '.PersonSchool::DIRECTOR.','.PersonSchool::PROFESOR.','.PersonSchool::OTRO.','.PersonSchool::PRESIDENTE
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

            'type_person' => 'required|starts_with:'.PersonSchool::DIRECTOR.','.PersonSchool::PROFESOR.','.PersonSchool::OTRO.','.PersonSchool::PRESIDENTE
        ];
        
        $this->validate($request, $rules, $messages);

        try {
            DB::beginTransaction();

                $update = Person::find($person_school->people_id);
                $update->cui = $request->cui;
                $update->name_one = $request->name_one;
                $update->name_two = $request->name_two;
                $update->last_name_one = $request->last_name_one;
                $update->last_name_two = $request->last_name_two;
                $update->direction = $request->direction_people;

                if(mb_strtolower($update->email) != mb_strtolower($request->email))
                    $update->email = $request->email;

                $update->municipalities_id = $request->municipalities_id_people;
                $update->save();

                $person_school->type_person = $request->type_person;
                $person_school->current = true;
                $person_school->people_id = $update->id;
                $person_school->save();

                $update_user = User::where('people_id',$person_school->people_id)->first();
                $enviar_correo = false;
                if(mb_strtolower($update_user->email) != mb_strtolower($update->email))
                {
                    $password = $this->generarPassword(16);
                    $update_user->email = $update->email;
                    $update_user->password = Hash::make($password);
                    $update_user->remember_token = Str::random(20);
                    $update_user->verified = User::USUARIO_NO_VERIFICADO;
                    $update_user->verification_token = User::generarVerificationToken();
                    $enviar_correo = true;
                }            
                $rol = Rol::select('id')->where('name',$person_school->type_person)->first();
                $update_user->rols_id = $rol->id;
                $update_user->save();  

                if(Rol::ROL_PRESIDENTE == $person_school->type_person){
                    $buscar = SchoolPresident::where('people_id', $person_school->people_id)->where('current', true)->get();

                    foreach ($buscar as $key => $value) {
                        $value->current = false;
                        $value->save(); 
                    }

                    if(!is_null($buscar)){
                        $buscar->current = true;
                        $buscar->save(); 
                    } else {
                        $insert = new SchoolPresident();
                        $insert->current = true;
                        $insert->schools_id = $person_school->schools_id;
                        $insert->people_id = $person_school->people_id;
                        $insert->save();                         
                    }
                }

            DB::commit();

            if($enviar_correo)
                Mail::to($update_user->email)->send(new WelcomeUser($update_user, $password));
                
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

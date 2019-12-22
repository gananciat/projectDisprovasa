<?php

namespace App\Http\Controllers\Sistema;

use App\User;
use App\Models\Rol;
use App\Models\Person;
use App\Models\School;
use App\Models\PhonePerson;
use App\Models\PhoneSchool;
use Illuminate\Support\Str;
use App\Models\PersonSchool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ApiController;
use App\Models\Order;

class SchoolController extends ApiController
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
        return $this->showAll(School::with(
            'municipality.departament',
            'person')
            ->get());
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
            'municipalities_id.exists'    => 'Debe de seleccionar la menos un municipio para la escuela.',
            'phone_school.number.digits_between'    => 'El número de teléfono de la escuela debe de tener 8 digitos.',
            'phone_school.companies_id.exists'    => 'Debe de seleccionar la menos una compania para el número de la escuela.',

            'cui'    => 'El número de DPI debe de tener entre :min y :max digitos.',
            'municipalities_id_people.exists'    => 'Debe de seleccionar la menos un municipio para la persona.',
            'phone_people.number.digits_between'    => 'El número de teléfono de la persona debe de tener 8 digitos.',
            'phone_people.companies_id.exists'    => 'Debe de seleccionar la menos una compania para el número de la persona.',

            'type_person.starts_with'    => 'Solo se aceptan los valores de '.PersonSchool::DIRECTOR.','.PersonSchool::PROFESOR.','.PersonSchool::OTRO
        ];

        $rules = [
            'municipalities_id' => 'required|integer|exists:municipalities,id',
            'name' => 'required|string|max:200|unique:schools,name',
            'logo' => 'required|string',
            'direction' => 'required|string|max:200',
            'nit' => 'required|string|max:15|unique:schools,nit',
            'code' => 'required|string|max:20|unique:schools,code',
            'phone_school.number.*' => 'required|digits_between:8,8',
            'phone_school.companies_id.*' => 'required|integer|exists:companies,id',

            'cui' => 'required|digits_between:13,15|unique:people,cui',
            'name_one' => 'required|string|max:25',
            'name_two' => 'nullable|string|max:50',
            'last_name_one' => 'required|string|max:25',
            'last_name_two' => 'nullable|string|max:50',
            'direction_people' => 'required|string|max:200',
            'email' => 'required|email|max:125|unique:people,email',
            'municipalities_id_people' => 'required|integer|exists:municipalities,id',
            'phone_people.number.*' => 'required|digits_between:8,8',
            'phone_people.companies_id.*' => 'required|integer|exists:companies,id',

            'type_person' => 'required|starts_with:'.PersonSchool::DIRECTOR.','.PersonSchool::PROFESOR.','.PersonSchool::OTRO
        ];
        
        $this->validate($request, $rules, $messages);

        try {
            DB::beginTransaction();
                $data = $request->all();

                $insert = new School();
                $insert->municipalities_id = $data->municipalities_id;
                $insert->name = $data->name;
                $insert->logo = $data->logo;
                $insert->direction = $data->direction;
                $insert->nit = $data->nit;
                $insert->code = $data->code;
                $insert->people_id = Auth::user()->people_id;
                $insert->current = true;
                $insert->save();

                for ($i=0; $i < count($data->phone_school); $i++) { 
                    $insert_phone_school = new PhoneSchool();
                    $insert_phone_school->number = $data->phone_school[$i]['number'];
                    $insert_phone_school->companies_id = $data->phone_school[$i]['companies_id'];
                    $insert_phone_school->schools_id = $insert->id;
                    $insert_phone_school->save();
                }

                $insert_people = Person::where('cui',$data->cui)->first();

                if(is_null($insert_people)) {
                    $insert_people = new Person();
                } else {
                    PhonePerson::where('people_id',$insert_people->id)->delete();
                }

                $insert_people->cui = $data->cui;
                $insert_people->name_one = $data->name_one;
                $insert_people->name_two = $data->name_two;
                $insert_people->last_name_one = $data->last_name_one;
                $insert_people->last_name_two = $data->last_name_two;
                $insert_people->direction = $data->direction_people;
                $insert_people->email = $data->email;
                $insert_people->municipalities_id = $data->municipalities_id_people;
                $insert_people->save();

                for ($i=0; $i < count($data->phone_people); $i++) { 
                    $insert_phone_people = new PhonePerson();
                    $insert_phone_people->number = $data->phone_people[$i]['number'];
                    $insert_phone_people->companies_id = $data->phone_people[$i]['companies_id'];
                    $insert_phone_people->people_id = $insert_people->id;
                    $insert_phone_people->save();
                }

                $asignar_persona_escuela = new PersonSchool();
                $asignar_persona_escuela->type_person = $data->type_person;
                $asignar_persona_escuela->current = true;
                $asignar_persona_escuela->schools_id = $insert->id;
                $asignar_persona_escuela->people_id = $insert_people->id;
                $asignar_persona_escuela->save();

                $insert_user = User::where('people_id',$insert_people->id)->first();

                if(is_null($insert_user)) {
                    $insert_user = new User();
                }

                $insert_user->email = $insert_people->email;
                $insert_user->password = Hash::make($this->generarPassword(16));
                $insert_user->remember_token = Str::random(20);
                $insert_user->verified = User::USUARIO_NO_VERIFICADO;
                $insert_user->verification_token = User::generarVerificationToken();
                $insert_user->admin = User::USUARIO_REGULAR;
                $insert_user->people_id = $insert_people->id;
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
     * @param  \App\Models\School  $school
     * @return \Illuminate\Http\Response
     */
    public function show(School $school)
    {
        return $this->showAll(School::with(
            'municipality.departament',
            'person',
            'phons.company',
            'people.user',
            'people.municipality.departament')
            ->where('id',$school->id)
            ->get());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\School  $school
     * @return \Illuminate\Http\Response
     */
    public function edit(School $school)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\School  $school
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, School $school)
    {
        $messages = [
            'municipalities_id.exists'    => 'Debe de seleccionar la menos un municipio para la escuela.',
        ];

        $rules = [
            'municipalities_id' => 'required|integer|exists:municipalities,id',
            'name' => 'required|string|max:200|unique:schools,name,'.$school->id,
            'logo' => 'required|string',
            'direction' => 'required|string|max:200',
            'nit' => 'required|string|max:15|unique:schools,nit,'.$school->id,
            'code' => 'required|string|max:20|unique:schools,code,'.$school->id,
        ];
        
        $this->validate($request, $rules, $messages);

        try {
            DB::beginTransaction();

                $school->municipalities_id = $request->municipalities_id;
                $school->name = $request->name;
                $school->logo = $request->logo;
                $school->direction = $request->direction;
                $school->nit = $request->nit;
                $school->code = $request->code;
                $school->people_id = Auth::user()->people_id;
                $school->current = true;

                if (!$school->isDirty()) {
                    return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
                }

                $school->save();

            DB::commit();

            return $this->showOne($school,201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(),403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\School  $school
     * @return \Illuminate\Http\Response
     */
    public function destroy(School $school)
    {
        try {
            DB::beginTransaction();

                $message = '';
                $orders = Order::where('schools_id',$school->id)->count();
        
                if($orders > 0) {
                    if($school->current == false) {
                        $message = $school->name.' fue dada de alta.';
                        $school->current = true;
                    }
                    else {
                        $message = $school->name.' fue dada de baja.';
                        $school->current = false;
                    }
                }
                else {
                    $message = $school->name.' fue eliminada.';
                    
                    $user = User::where('people_id',PersonSchool::select('people_id')->where('schools_id', $school->id)->first())->first();
                    $user->password = Hash::make($this->generarPassword(16));
                    $user->remember_token = Str::random(20);
                    $user->verified = User::USUARIO_NO_VERIFICADO;
                    $user->verification_token = User::generarVerificationToken();
                    $user->admin = User::USUARIO_REGULAR;
                    $user->save();   
                    
                    PersonSchool::where('schools_id', $school->id)->delete();
                    PhoneSchool::where('schools_id', $school->id)->delete();
                    $school->delete();
                }
        
                return $this->errorResponse($message, 201);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(),403);
        }
    }
}

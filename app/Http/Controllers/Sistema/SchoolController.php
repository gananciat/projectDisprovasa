<?php

namespace App\Http\Controllers\Sistema;

use App\User;
use App\Models\Rol;
use App\Models\Order;
use App\Models\Person;
use App\Models\School;
use App\Models\PhonePerson;
use App\Models\PhoneSchool;
use Illuminate\Support\Str;
use App\Models\PersonSchool;
use Illuminate\Http\Request;
use App\Models\SchoolPresident;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Storage;

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
            'bill' => 'required|string|max:200|unique:schools,bill',
            'direction' => 'required|string|max:200',
            'nit' => 'required|string|max:15|unique:schools,nit',
            'code_high_school' => 'nullable|string|max:20',
            'code_primary' => 'nullable|string|max:20',
            'phone_school.number.*' => 'required|digits_between:8,8',
            'phone_school.companies_id.*' => 'required|integer|exists:companies,id',

            'president' => 'required|boolean',

            'cui' => 'required|digits_between:13,15',
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
                if(!is_null(School::where('code_high_school',$request->code_high_school)->first()) && $request->code_high_school != null)
                    return $this->errorResponse('El cóigo de preprimaria ya existe registrado.',403);

                if(!is_null(School::where('code_primary',$request->code_primary)->first()) && $request->code_primary != null)
                    return $this->errorResponse('El cóigo de primaria ya existe registrado.',403);

                $imagePath = '';
                if($request->logo != null || $request->logo != ''){
                    if (preg_match('/^data:image\/(\w+);base64,/', $request->logo)) {
                        $data_img = substr($request->logo, strpos($request->logo, ',') + 1);
                        $data_img = base64_decode($data_img);
                        $imagePath = $request->nit.'_'.time().'.png';
                        Storage::disk('images')->put($imagePath, $data_img);
                    } 
                }

                $insert = new School();
                $insert->municipalities_id = $request->municipalities_id;
                $insert->name = $request->name;
                $insert->bill = $request->bill;
                $insert->logo = 'img/school_images/'.$imagePath;
                $insert->direction = $request->direction;
                $insert->nit = $request->nit;
                $insert->code_high_school = $request->code_high_school;
                $insert->code_primary = $request->code_primary;
                $insert->people_id = Auth::user()->people_id;
                $insert->current = true;
                $insert->save();

                for ($i=0; $i < count($request->phone_school); $i++) { 
                    $insert_phone_school = new PhoneSchool();
                    $insert_phone_school->number = $request->phone_school[$i]['number'];
                    $insert_phone_school->companies_id = $request->phone_school[$i]['companies_id'];
                    $insert_phone_school->schools_id = $insert->id;
                    $insert_phone_school->save();
                }

                $insert_people = Person::where('cui',$request->cui)->first();

                if(is_null($insert_people)) {
                    $insert_people = new Person();
                } else {
                    PhonePerson::where('people_id',$insert_people->id)->delete();
                }

                $insert_people->cui = $request->cui;
                $insert_people->name_one = $request->name_one;
                $insert_people->name_two = $request->name_two;
                $insert_people->last_name_one = $request->last_name_one;
                $insert_people->last_name_two = $request->last_name_two;
                $insert_people->direction = $request->direction_people;
                $insert_people->email = $request->email;
                $insert_people->municipalities_id = $request->municipalities_id_people;
                $insert_people->save();

                for ($i=0; $i < count($request->phone_people); $i++) { 
                    $insert_phone_people = new PhonePerson();
                    $insert_phone_people->number = $request->phone_people[$i]['number'];
                    $insert_phone_people->companies_id = $request->phone_people[$i]['companies_id'];
                    $insert_phone_people->people_id = $insert_people->id;
                    $insert_phone_people->save();
                }

                $asignar_persona_escuela = new PersonSchool();
                $asignar_persona_escuela->type_person = $request->type_person;
                $asignar_persona_escuela->current = true;
                $asignar_persona_escuela->schools_id = $insert->id;
                $asignar_persona_escuela->people_id = $insert_people->id;
                $asignar_persona_escuela->save();

                if($request->president == true) 
                {
                    $asignar_presidente_escuela = new SchoolPresident();
                    $asignar_presidente_escuela->current = true;
                    $asignar_presidente_escuela->schools_id = $insert->id;
                    $asignar_presidente_escuela->people_id = $insert_people->id;
                    $asignar_presidente_escuela->save(); 
                }

                $insert_user = User::where('people_id',$insert_people->id)->first();

                if(is_null($insert_user)) {
                    $insert_user = new User();
                }

                $rol = Rol::select('id')->where('name',$asignar_persona_escuela->type_person)->first();
                $password = $this->generarPassword(16);
                $insert_user->email = $insert_people->email;
                $insert_user->password = $password;
                $insert_user->remember_token = Str::random(20);
                $insert_user->verified = User::USUARIO_NO_VERIFICADO;
                $insert_user->verification_token = User::generarVerificationToken();
                $insert_user->admin = User::USUARIO_REGULAR;
                $insert_user->people_id = $insert_people->id;
                $insert_user->rols_id = $rol->id;
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
        $school = School::with([
                                'municipality:id,name,departaments_id',
                                'municipality.departament:id,name',
                                'person:id,cui,name_one,name_two,last_name_one,last_name_two',
                                'phons:id,number,companies_id,schools_id',
                                'phons.company:id,name',
                                'people' => function ($query) {
                                    $query->where('current', true);
                                },
                                'people.person:id,cui,name_one,name_two,last_name_one,last_name_two,email,municipalities_id,direction',
                                'people.person.phons:id,number,companies_id,people_id',
                                'people.person.phons.company:id,name',
                                'people.person.municipality:id,name,departaments_id',
                                'people.person.municipality.departament:id,name',
                                'people.person.user:id,verified,people_id,rols_id,current_school',
                                'people.person.user.rol:id,name',
                                'people.person.school_president:id,current,people_id,schools_id'
                            ])
                            ->where('id',$school->id)
                            ->get();
        return $this->showAll($school);
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
            'bill' => 'required|string|max:200|unique:schools,bill,'.$school->id,
            'direction' => 'required|string|max:200',
            'nit' => 'required|string|max:15|unique:schools,nit,'.$school->id,
            'code_high_school' => 'nullable|string|max:20',
            'code_primary' => 'nullable|string|max:20',
        ];
        
        $this->validate($request, $rules, $messages);

        try {
            DB::beginTransaction();

                if(!is_null(School::where('code_high_school',$school->code_high_school)->first()) && $school->code_high_school != $request->code_high_school && $request->code_high_school != null)
                    return $this->errorResponse('El cóigo de preprimaria ya existe registrado.',403);

                if(!is_null(School::where('code_primary',$school->code_primary)->first()) && $school->code_primary != $request->code_primary && $request->code_primary != null)
                    return $this->errorResponse('El cóigo de primaria ya existe registrado.',403);

                $school->municipalities_id = $request->municipalities_id;
                $school->name = $request->name;
                $school->bill = $request->bill;
                $school->direction = $request->direction;
                $school->nit = $request->nit;
                $school->code_high_school = $request->code_high_school;
                $school->code_primary = $request->code_primary;
                $school->people_id = Auth::user()->people_id;

                if($request->logo != null || $request->logo != ''){
                    $imagePath = '';
                    if (preg_match('/^data:image\/(\w+);base64,/', $request->logo)) {
                        $data = substr($request->logo, strpos($request->logo, ',') + 1);
                        $data = base64_decode($data);
                        $imagePath = $request->nit.'_'.time().'.png';
                        Storage::disk('images')->put($imagePath, $data);
                    }
                    $school->logo = 'img/school_images/'.$imagePath;
                }

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

                $asignados = PersonSchool::where('schools_id',$school->id)->where('current',true)->get();

                foreach ($asignados as $key => $value) {
                    $user = User::where('people_id',$value->people_id)->first();
                    $user->password = Hash::make($this->generarPassword(16));
                    $user->remember_token = Str::random(20);
                    $user->verified = User::USUARIO_NO_VERIFICADO;
                    $user->verification_token = User::generarVerificationToken();
                    $user->admin = User::USUARIO_REGULAR;
                    $user->save(); 
                }                

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
                    
                    PersonSchool::where('schools_id', $school->id)->delete();
                    PhoneSchool::where('schools_id', $school->id)->delete();
                    $school->delete();
                }
        
                return $this->errorResponse($message, 422);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(),403);
        }
    }
}

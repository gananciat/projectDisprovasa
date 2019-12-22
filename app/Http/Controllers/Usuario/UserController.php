<?php

namespace App\Http\Controllers\Usuario;

use App\User;
use App\Models\Rol;
use App\Models\Person;
use App\Models\PhonePerson;
use Illuminate\Support\Str;
use App\Models\PersonSchool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ApiController;

class UserController extends ApiController
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
        return $this->showAll(User::with('people.municipality.departament','rol')->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->showAll(Rol::where('administrative',true)->get());
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
            'cui'    => 'El número de DPI debe de tener entre :min y :max digitos.',
            'municipalities_id_people.exists'    => 'Debe de seleccionar la menos un municipio para la persona.',
            'rols_id.exists'    => 'Debe de seleccionar la menos un rol para la persona.',
            'phone_people.number.digits_between'    => 'El número de teléfono de la persona debe de tener 8 digitos.',
            'phone_people.companies_id.exists'    => 'Debe de seleccionar la menos una compania para el número de la persona.',
        ];

        $rules = [
            'cui' => 'required|digits_between:13,15|unique:people,cui',
            'name_one' => 'required|string|max:25',
            'name_two' => 'nullable|string|max:50',
            'last_name_one' => 'required|string|max:25',
            'last_name_two' => 'nullable|string|max:50',
            'direction_people' => 'required|string|max:200',
            'email' => 'required|email|max:125|unique:people,email',
            'municipalities_id_people' => 'required|integer|exists:municipalities,id',
            'rols_id' => 'required|integer|exists:rols,id',
            'phone_people.number.*' => 'required|digits_between:8,8',
            'phone_people.companies_id.*' => 'required|integer|exists:companies,id',
        ];
        
        $this->validate($request, $rules, $messages);

        try {
            DB::beginTransaction();
                $data = $request->all();

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

                $insert = User::where('people_id',$insert_people->id)->first();

                if(is_null($insert)) {
                    $insert = new User();
                }

                $insert->email = $insert_people->email;
                $insert->password = Hash::make($this->generarPassword(16));
                $insert->remember_token = Str::random(20);
                $insert->verified = User::USUARIO_NO_VERIFICADO;
                $insert->verification_token = User::generarVerificationToken();
                $insert->admin = User::USUARIO_REGULAR;
                $insert->people_id = $insert_people->id;
                $insert->rols_id = $data->rols_id;
                $insert->save();                

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
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $this->showAll(User::with('people.municipality.departament','rol')->where('id',$user->id)->get());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $messages = [
            'cui'    => 'El número de DPI debe de tener entre :min y :max digitos.',
            'municipalities_id_people.exists'    => 'Debe de seleccionar la menos un municipio para la persona.',
            'rols_id.exists'    => 'Debe de seleccionar la menos un rol para la persona.',
            'phone_people.number.digits_between'    => 'El número de teléfono de la persona debe de tener 8 digitos.',
            'phone_people.companies_id.exists'    => 'Debe de seleccionar la menos una compania para el número de la persona.',
        ];

        $rules = [
            'cui' => 'required|digits_between:13,15|unique:people,cui,'.$user->people_id,
            'name_one' => 'required|string|max:25',
            'name_two' => 'nullable|string|max:50',
            'last_name_one' => 'required|string|max:25',
            'last_name_two' => 'nullable|string|max:50',
            'direction_people' => 'required|string|max:200',
            'email' => 'required|email|max:125|unique:people,email,'.$user->people_id,
            'municipalities_id_people' => 'required|integer|exists:municipalities,id',
            'rols_id' => 'required|integer|exists:rols,id',
            'phone_people.number.*' => 'required|digits_between:8,8',
            'phone_people.companies_id.*' => 'required|integer|exists:companies,id',
        ];
        
        $this->validate($request, $rules, $messages);

        try {
            DB::beginTransaction();
                $data = $request->all();

                $update_people = Person::find($user->people_id);
                PhonePerson::where('people_id',$update_people->id)->delete();

                $update_people->cui = $data->cui;
                $update_people->name_one = $data->name_one;
                $update_people->name_two = $data->name_two;
                $update_people->last_name_one = $data->last_name_one;
                $update_people->last_name_two = $data->last_name_two;
                $update_people->direction = $data->direction_people;
                $update_people->email = $data->email;
                $update_people->municipalities_id = $data->municipalities_id_people;
                $update_people->save();

                for ($i=0; $i < count($data->phone_people); $i++) { 
                    $update_phone_people = new PhonePerson();
                    $update_phone_people->number = $data->phone_people[$i]['number'];
                    $update_phone_people->companies_id = $data->phone_people[$i]['companies_id'];
                    $update_phone_people->people_id = $update_people->id;
                    $update_phone_people->save();
                }     
                
                if($user->email != $update_people->email) 
                {
                    $user->email = $update_people->email;
                    $user->password = Hash::make($this->generarPassword(16));
                    $user->remember_token = Str::random(20);
                    $user->verified = User::USUARIO_NO_VERIFICADO;
                    $user->verification_token = User::generarVerificationToken();
                    $user->admin = User::USUARIO_REGULAR;
                    $user->rols_id = $data->rols_id;
                    $user->save(); 
                } else {
                    $user->rols_id = $data->rols_id;
                    $user->save(); 
                }

            DB::commit();

            return $this->showOne($update_people,201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(),403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->password = Hash::make($this->generarPassword(16));
        $user->remember_token = Str::random(20);
        $user->verified = User::USUARIO_NO_VERIFICADO;
        $user->verification_token = User::generarVerificationToken();
        $user->save();
        return $this->showOne($user,201);
    }
}

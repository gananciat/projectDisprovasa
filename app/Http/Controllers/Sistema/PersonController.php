<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\PhonePerson;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PersonController extends  ApiController
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
        $persons = Person::with('user.rol','phones.company','municipality.departament')->get();
        $persons = $persons->where('user.current_school',false)->values();

        return $this->showAll($persons);
    }


    //mostrar compra
    public function show(Person $person){
        return $this->showOne($person);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'cui'=>'required',
            'name_one'=>'required',
            'last_name_one'=>'required',
            'direction'=>'required',
            'email'=>'required|email|max:125|unique:people,email',
            'municipalities_id' => 'required|integer|exists:municipalities,id'
        ];
        
        $this->validate($request, $rules);

        DB::beginTransaction();
            $data = $request->all();
            $person = Person::create($data);

            foreach ($request->phones as $phone) {
                PhonePerson::create([
                    'companies_id'=>$phone['companies_id'],
                    'people_id'=>$person->id,
                    'number' => $phone['number']
                ]);
            }

            $year = Carbon::now()->year;
            $password = ucfirst(strtolower($request->name_one)).$year;

            $insert_user = new User;

            $insert_user->email = $request->email;
            $insert_user->password = $password;
            $insert_user->remember_token = Str::random(20);
            $insert_user->verified = User::USUARIO_VERIFICADO;
            $insert_user->verification_token = User::generarVerificationToken();
            $insert_user->admin = User::USUARIO_REGULAR;
            $insert_user->people_id = $person->id;
            $insert_user->rols_id = $request->rols_id;
            $insert_user->current_school = 0;
            $insert_user->save();
        

        DB::commit();

        return $this->showOne($person,201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Purcharse  $purcharse
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Person $person)
    {

        $rules = [
            'cui'=>'required',
            'name_one'=>'required',
            'last_name_one'=>'required',
            'direction'=>'required',
            'email'=>'required|email|max:125|unique:people,email,'.$person->id,
            'municipalities_id' => 'required|integer|exists:municipalities,id'
        ];
        
        $this->validate($request, $rules);
        DB::beginTransaction();
            $person->cui = $request->cui;
            $person->name_one = $request->name_one;
            $person->name_two = $request->name_two;
            $person->last_name_one = $request->last_name_one;
            $person->last_name_two = $request->last_name_two;
            $person->direction = $request->direction;
            $person->municipalities_id = $request->municipalities_id;

            $person->phones()->delete();

            foreach ($request->phones as $phone) {
                PhonePerson::create([
                    'number'=>$phone['number'],
                    'companies_id'=>$phone['companies_id'],
                    'people_id'=>$person->id
                ]);
            }

            $user = User::where('email',$person->email)->first();
            $user->rols_id = $request->rols_id;

            if($person->email !== $request->email){
                $person->email = $request->email;
                $user->email = $request->email;

            }
            $user->save();
            $person->save();
        DB::commit();

        return $this->showOne($person,201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purcharse  $purcharse
     * @return \Illuminate\Http\Response
     */
    public function destroy(Person $person)
    {
        DB::beginTransaction();
            $person->user()->delete();
            $person->phones()->delete();
            $person->delete();
        DB::commit();

        return $this->showOne($person,201);
    }
}

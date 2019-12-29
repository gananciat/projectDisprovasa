<?php

namespace App\Imports;

use App\Models\Municipality;
use App\User;
use App\Models\Rol;
use App\Models\Person;
use App\Models\School;
use App\Models\PhonePerson;
use App\Models\PhoneSchool;
use Illuminate\Support\Str;
use App\Models\PersonSchool;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class EscuelaImport implements ToCollection
{
    public function collection(Collection $collection)
    {
        $rol = Rol::select('id')->where('name','PRESIDENTE')->first();
        foreach ($collection as $key => $value) {
            if($value[0] != ''){
                DB::beginTransaction();
                    $municipality = Municipality::where('name',mb_strtoupper($value[3]))->first();
                    $insert = new School();
                    $insert->municipalities_id = $municipality->id;
                    $insert->name = mb_strtoupper($value[0]);
                    $insert->bill = mb_strtoupper($value[1]);
                    $insert->logo = null;
                    $insert->direction = mb_strtoupper($value[2]);
                    $insert->nit = $value[4];
                    $insert->code_high_school = $value[5];
                    $insert->code_primary = $value[6];
                    $insert->people_id = 1;
                    $insert->current = true;
                    $insert->save();

                    $insert_phone_school = new PhoneSchool();
                    $insert_phone_school->number = str_replace('-','',$value[11]);
                    $insert_phone_school->companies_id = 1;
                    $insert_phone_school->schools_id = $insert->id;
                    $insert_phone_school->save();

                    $insert_people = new Person();
                    $insert_people->cui = Str::random(13);
                    $insert_people->name_one = mb_strtoupper($value[7]);
                    $insert_people->name_two = mb_strtoupper($value[8]);
                    $insert_people->last_name_one = mb_strtoupper($value[9]);
                    $insert_people->last_name_two = mb_strtoupper($value[10]);
                    $insert_people->direction = mb_strtoupper($value[2]);
                    $insert_people->email = mb_strtolower($value[7]).'@gmail.com';
                    $insert_people->municipalities_id = $municipality->id;
                    $insert_people->save();

                    $insert_phone_people = new PhonePerson();
                    $insert_phone_people->number = $insert_phone_school->number;
                    $insert_phone_people->companies_id = 2;
                    $insert_phone_people->people_id = $insert_people->id;
                    $insert_phone_people->save();

                    $asignar_persona_escuela = new PersonSchool();
                    $asignar_persona_escuela->type_person = PersonSchool::OTRO;
                    $asignar_persona_escuela->current = true;
                    $asignar_persona_escuela->schools_id = $insert->id;
                    $asignar_persona_escuela->people_id = $insert_people->id;
                    $asignar_persona_escuela->save();

                    $insert_user = new User();
                    $insert_user->email = $insert_people->email;
                    $insert_user->password = 'secret';
                    $insert_user->remember_token = Str::random(20);
                    $insert_user->verified = User::USUARIO_VERIFICADO;
                    $insert_user->verification_token = User::generarVerificationToken();
                    $insert_user->admin = User::USUARIO_REGULAR;
                    $insert_user->people_id = $insert_people->id;
                    $insert_user->rols_id = $rol->id;
                    $insert_user->save();    
                    
                    echo $insert->name.' -- PRESIDENTE: '.$insert_people->name_one.' '.$insert_people->last_name_one.' -- USUARIO: '.$insert_user->email.' -- PASSWORD: secret'.PHP_EOL;

                DB::commit();
            }
        }
    }
}

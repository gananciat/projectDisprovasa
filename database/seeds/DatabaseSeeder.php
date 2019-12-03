<?php

use App\Models\Departament;
use App\Models\Municipality;
use App\Models\Person;
use App\Models\Rol;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        //$this->call([UsersTableSeeder::class]);

        $insert_departament = new Departament();
        $insert_departament->name = 'Santa Rosa';
        $insert_departament->save();

        $insert_municipality = new Municipality();
        $insert_municipality->name = 'Chiquimulilla';
        $insert_municipality->departaments_id = $insert_departament->id;
        $insert_municipality->save();        

        $insert_rol = new Rol();
        $insert_rol->name = 'administrador';
        $insert_rol->administrative = true;
        $insert_rol->save();

        $insert_person = new Person();
        $insert_person->cui = '12345678912345';
        $insert_person->name_one = 'Héctor';
        $insert_person->name_two = 'Renato';
        $insert_person->last_name_one = 'de la Cruz';
        $insert_person->last_name_two = 'Ojeda';
        $insert_person->direction = 'barrio belén';
        $insert_person->email = 'emisor.tarea@gmail.com';
        $insert_person->municipalities_id = $insert_municipality->id;
        $insert_person->save();

        $insert_user = new User();
        $insert_user->email = $insert_person->email;
        $insert_user->password = 'secret';
        $insert_user->remember_token = Str::random(20);
        $insert_user->verified = User::USUARIO_VERIFICADO;
        $insert_user->verification_token = User::generarVerificationToken();
        $insert_user->admin = User::USUARIO_ADMINISTRADOR;
        $insert_user->people_id = $insert_person->id;
        $insert_user->rols_id = $insert_rol->id;
        $insert_user->save();
    }
}

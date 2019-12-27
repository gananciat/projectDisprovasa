<?php

use App\Models\Departament;
use App\Models\Month;
use App\Models\Municipality;
use App\Models\Person;
use App\Models\Rol;
use App\Models\Year;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        //$this->call([UsersTableSeeder::class]);

        for ($i=18; $i < 50; $i++) { 
            $new = new Year();
            $new->year = '20'.$i;
            $new->save();
        }

        $insert_month = new Month();
        $insert_month->month = 'Enero';
        $insert_month->save();

        $insert_month = new Month();
        $insert_month->month = 'Febrero';
        $insert_month->save();

        $insert_month = new Month();
        $insert_month->month = 'Marzo';
        $insert_month->save();

        $insert_month = new Month();
        $insert_month->month = 'Abril';
        $insert_month->save();

        $insert_month = new Month();
        $insert_month->month = 'Mayo';
        $insert_month->save();

        $insert_month = new Month();
        $insert_month->month = 'Junio';
        $insert_month->save();

        $insert_month = new Month();
        $insert_month->month = 'Julio';
        $insert_month->save();

        $insert_month = new Month();
        $insert_month->month = 'Agosto';
        $insert_month->save();

        $insert_month = new Month();
        $insert_month->month = 'Septiembre';
        $insert_month->save();

        $insert_month = new Month();
        $insert_month->month = 'Octubre';
        $insert_month->save();

        $insert_month = new Month();
        $insert_month->month = 'Noviembre';
        $insert_month->save();

        $insert_month = new Month();
        $insert_month->month = 'Diciembre';
        $insert_month->save();

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

        $insert_rol = new Rol();
        $insert_rol->name = 'director';
        $insert_rol->administrative = false;
        $insert_rol->save();        

        $insert_rol = new Rol();
        $insert_rol->name = 'profesor';
        $insert_rol->administrative = false;
        $insert_rol->save();              

        $insert_rol = new Rol();
        $insert_rol->name = 'presidente';
        $insert_rol->administrative = false;
        $insert_rol->save();  

        $insert_rol = new Rol();
        $insert_rol->name = 'otro';
        $insert_rol->administrative = false;
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

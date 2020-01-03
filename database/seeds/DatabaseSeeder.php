<?php

use App\User;
use App\Models\Rol;
use App\Models\Year;
use App\Models\Month;
use App\Models\Person;
use App\Models\Company;
use App\Imports\UtilImport;
use Illuminate\Support\Str;
use App\Imports\MarcaImport;
use App\Models\Municipality;
use Illuminate\Database\Seeder;
use App\Imports\CategoriaImport;
use App\Imports\GratuidadImport;
use App\Imports\MunicipioImport;
use App\Imports\AlimentacionImport;
use App\Imports\DepartamentoImport;
use App\Imports\EscuelaImport;
use App\Models\OrderStatus;
use Maatwebsite\Excel\Facades\Excel;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        //$this->call([UsersTableSeeder::class]);

        for ($i=18; $i < 50; $i++) { 
            $new = new Year();
            $new->year = '20'.$i;
            $new->save();
            echo 'AÑO: '.$new->year.PHP_EOL;
        }

        $insert_month = new Month();
        $insert_month->month = 'Enero';
        $insert_month->save();
        echo 'MES: '.$insert_month->month.PHP_EOL;

        $insert_month = new Month();
        $insert_month->month = 'Febrero';
        $insert_month->save();
        echo 'MES: '.$insert_month->month.PHP_EOL;

        $insert_month = new Month();
        $insert_month->month = 'Marzo';
        $insert_month->save();
        echo 'MES: '.$insert_month->month.PHP_EOL;

        $insert_month = new Month();
        $insert_month->month = 'Abril';
        $insert_month->save();
        echo 'MES: '.$insert_month->month.PHP_EOL;

        $insert_month = new Month();
        $insert_month->month = 'Mayo';
        $insert_month->save();
        echo 'MES: '.$insert_month->month.PHP_EOL;

        $insert_month = new Month();
        $insert_month->month = 'Junio';
        $insert_month->save();
        echo 'MES: '.$insert_month->month.PHP_EOL;

        $insert_month = new Month();
        $insert_month->month = 'Julio';
        $insert_month->save();
        echo 'MES: '.$insert_month->month.PHP_EOL;

        $insert_month = new Month();
        $insert_month->month = 'Agosto';
        $insert_month->save();
        echo 'MES: '.$insert_month->month.PHP_EOL;

        $insert_month = new Month();
        $insert_month->month = 'Septiembre';
        $insert_month->save();
        echo 'MES: '.$insert_month->month.PHP_EOL;

        $insert_month = new Month();
        $insert_month->month = 'Octubre';
        $insert_month->save();
        echo 'MES: '.$insert_month->month.PHP_EOL;

        $insert_month = new Month();
        $insert_month->month = 'Noviembre';
        $insert_month->save();
        echo 'MES: '.$insert_month->month.PHP_EOL;

        $insert_month = new Month();
        $insert_month->month = 'Diciembre';
        $insert_month->save();
        echo 'MES: '.$insert_month->month.PHP_EOL;

        $insert_company = new Company();
        $insert_company->name = 'tigo';
        $insert_company->save();
        echo 'COMPANIA: '.$insert_company->name.PHP_EOL;

        $insert_company = new Company();
        $insert_company->name = 'claro';
        $insert_company->save();
        echo 'COMPANIA: '.$insert_company->name.PHP_EOL;

        $insert_company = new Company();
        $insert_company->name = 'movistar';
        $insert_company->save();
        echo 'COMPANIA: '.$insert_company->name.PHP_EOL;

        Excel::import(new DepartamentoImport, 'database/seeds/Catalogos/Departamentos.xlsx');
        Excel::import(new MunicipioImport, 'database/seeds/Catalogos/Municipios.xlsx');
        Excel::import(new CategoriaImport, 'database/seeds/Catalogos/Categorias.xlsx');
        Excel::import(new MarcaImport, 'database/seeds/Catalogos/Marcas.xlsx');
        Excel::import(new AlimentacionImport, 'database/seeds/Catalogos/Alimentacion.xlsx');
        Excel::import(new GratuidadImport, 'database/seeds/Catalogos/Gratuidad.xlsx');
        Excel::import(new UtilImport, 'database/seeds/Catalogos/Utiles.xlsx');

        $insert_rol = new Rol();
        $insert_rol->name = 'administrador';
        $insert_rol->administrative = true;
        $insert_rol->save();
        echo 'ROL: '.$insert_rol->name.PHP_EOL;        

        $insert_rol = new Rol();
        $insert_rol->name = 'director';
        $insert_rol->administrative = false;
        $insert_rol->save();    
        echo 'ROL: '.$insert_rol->name.PHP_EOL;        

        $insert_rol = new Rol();
        $insert_rol->name = 'profesor';
        $insert_rol->administrative = false;
        $insert_rol->save();  
        echo 'ROL: '.$insert_rol->name.PHP_EOL;                

        $insert_rol = new Rol();
        $insert_rol->name = 'presidente';
        $insert_rol->administrative = false;
        $insert_rol->save();  
        echo 'ROL: '.$insert_rol->name.PHP_EOL;    

        $insert_rol = new Rol();
        $insert_rol->name = 'otro';
        $insert_rol->administrative = false;
        $insert_rol->save();
        echo 'ROL: '.$insert_rol->name.PHP_EOL;

        $municipality =  Municipality::where('name', 'CHIQUIMULILLA')->first();
        $insert_person = new Person();
        $insert_person->cui = '12345678912345';
        $insert_person->name_one = 'Héctor';
        $insert_person->name_two = 'Renato';
        $insert_person->last_name_one = 'de la Cruz';
        $insert_person->last_name_two = 'Ojeda';
        $insert_person->direction = 'barrio belén';
        $insert_person->email = 'emisor.tarea@gmail.com';
        $insert_person->municipalities_id = $municipality->id;
        $insert_person->save();  

        $insert_user = new User();
        $insert_user->email = $insert_person->email;
        $insert_user->password = 'secret';
        $insert_user->remember_token = Str::random(20);
        $insert_user->verified = User::USUARIO_VERIFICADO;
        $insert_user->verification_token = User::generarVerificationToken();
        $insert_user->admin = User::USUARIO_ADMINISTRADOR;
        $insert_user->people_id = $insert_person->id;
        $insert_user->rols_id = 1;
        $insert_user->current_school = 0;
        $insert_user->save();
        echo 'CUI: '.$insert_person->cui.'PERSONA: '.$insert_person->name_one.' '.$insert_person->last_name_one.' ROL: ADMINISTRADOR'.PHP_EOL;  
    
    
        Excel::import(new EscuelaImport, 'database/seeds/Catalogos/Escuelas.xlsx');  

        $insert_order = new OrderStatus();
        $insert_order->status = 'pedido';
        $insert_order->save();  
        echo 'ESTADO DE LA ORDEN: '.$insert_order->status.PHP_EOL;

        $insert_order = new OrderStatus();
        $insert_order->status = 'en proceso';
        $insert_order->save();  
        echo 'ESTADO DE LA ORDEN: '.$insert_order->status.PHP_EOL;

        $insert_order = new OrderStatus();
        $insert_order->status = 'completado';
        $insert_order->save();  
        echo 'ESTADO DE LA ORDEN: '.$insert_order->status.PHP_EOL;
    }
}

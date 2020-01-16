<?php

use App\User;
use App\Models\Rol;
use App\Models\Year;
use App\Models\Month;
use App\Models\Order;
use App\Models\Person;
use App\Models\School;
use App\Models\Balance;
use App\Models\Company;
use App\Imports\MenuImport;
use App\Imports\UtilImport;
use App\Models\DetailOrder;
use App\Models\OrderStatus;
use App\Models\Reservation;
use Illuminate\Support\Str;
use App\Imports\MarcaImport;
use App\Models\Municipality;
use App\Models\ProgressOrder;
use App\Imports\EscuelaImport;
use App\Models\CalendarSchool;
use Illuminate\Database\Seeder;
use App\Imports\CategoriaImport;
use App\Imports\GratuidadImport;
use App\Imports\MunicipioImport;
use App\Imports\AlimentacionImport;
use App\Imports\DepartamentoImport;
use Maatwebsite\Excel\Facades\Excel;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
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
        $insert_rol->name = 'gerente';
        $insert_rol->administrative = true;
        $insert_rol->save();
        echo 'ROL: '.$insert_rol->name.PHP_EOL;

        $insert_rol = new Rol();
        $insert_rol->name = 'bodega';
        $insert_rol->administrative = true;
        $insert_rol->save();
        echo 'ROL: '.$insert_rol->name.PHP_EOL; 

        $insert_rol = new Rol();
        $insert_rol->name = 'supervisor';
        $insert_rol->administrative = true;
        $insert_rol->save();
        echo 'ROL: '.$insert_rol->name.PHP_EOL;

        $insert_rol = new Rol();
        $insert_rol->name = 'compra';
        $insert_rol->administrative = true;
        $insert_rol->save();
        echo 'ROL: '.$insert_rol->name.PHP_EOL;  

        $insert_rol = new Rol();
        $insert_rol->name = 'facturador';
        $insert_rol->administrative = true;
        $insert_rol->save();
        echo 'ROL: '.$insert_rol->name.PHP_EOL;  

        $insert_rol = new Rol();
        $insert_rol->name = 'repartidor';
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

        $municipality =  Municipality::where('name', 'CHIQUIMULILLA')->first();
        $insert_person = new Person();
        $insert_person->cui = '12345678912346';
        $insert_person->name_one = 'Juan';
        $insert_person->name_two = 'Renato';
        $insert_person->last_name_one = 'Estrada';
        $insert_person->last_name_two = 'Ojeda';
        $insert_person->direction = 'barrio belén';
        $insert_person->email = 'gerente@gmail.com';
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
        $insert_user->rols_id = 2;
        $insert_user->current_school = 0;
        $insert_user->save();

        $municipality =  Municipality::where('name', 'CHIQUIMULILLA')->first();
        $insert_person = new Person();
        $insert_person->cui = '12345678912347';
        $insert_person->name_one = 'Pedro';
        $insert_person->name_two = 'Renato';
        $insert_person->last_name_one = 'de la Cruz';
        $insert_person->last_name_two = 'Ojeda';
        $insert_person->direction = 'barrio belén';
        $insert_person->email = 'compra@gmail.com';
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
        $insert_user->rols_id = 5;
        $insert_user->current_school = 0;
        $insert_user->save();
        
        echo 'CUI: '.$insert_person->cui.'PERSONA: '.$insert_person->name_one.' '.$insert_person->last_name_one.' ROL: ADMINISTRADOR'.PHP_EOL;  
    
        Excel::import(new MenuImport, 'database/seeds/Catalogos/Menu.xlsx'); 

        Excel::import(new EscuelaImport, 'database/seeds/Catalogos/Escuelas.xlsx');  

        $schools = School::all();

        foreach ($schools as $key => $value) 
        {
            $fecha_p = date('Y-m-d');
            $fecha_f = date('Y-m-d', strtotime('+55 day',strtotime($fecha_p)));
            if(!is_null($value->code_high_school)){
                $insert_balace = new Balance();
                $insert_balace->balance = random_int(15000,30000);
                $insert_balace->start_date = $fecha_p;
                $insert_balace->end_date = $fecha_f;
                $insert_balace->schools_id = $value->id;
                $insert_balace->people_id = 1;
                $insert_balace->year = date('Y');
                $insert_balace->subtraction = 0;
                $insert_balace->subtraction_temporary = 0;
                $insert_balace->code = $value->code_high_school;
                $insert_balace->type_balance = Balance::ALIMENTACION;
                $insert_balace->current = true;
                $insert_balace->disbursement_id = 1;
                $insert_balace->save();
                echo 'CODIGO PREPA: '.$insert_balace->code.' ESCUELA: '.$value->name.' MONTO Q: '.$insert_balace->balance.' ALIMENTACION'.PHP_EOL;

                $insert_balace = new Balance();
                $insert_balace->balance = random_int(15000,30000);
                $insert_balace->start_date = $fecha_p;
                $insert_balace->end_date = $fecha_f;
                $insert_balace->schools_id = $value->id;
                $insert_balace->people_id = 1;
                $insert_balace->year = date('Y');
                $insert_balace->subtraction = 0;
                $insert_balace->subtraction_temporary = 0;
                $insert_balace->code = $value->code_high_school;
                $insert_balace->type_balance = Balance::GRATUIDAD;
                $insert_balace->current = true;
                $insert_balace->disbursement_id = 1;
                $insert_balace->save();
                echo 'CODIGO PREPA: '.$insert_balace->code.' ESCUELA: '.$value->name.' MONTO Q: '.$insert_balace->balance.' GRATUIDAD'.PHP_EOL;

                $insert_balace = new Balance();
                $insert_balace->balance = random_int(15000,30000);
                $insert_balace->start_date = $fecha_p;
                $insert_balace->end_date = $fecha_f;
                $insert_balace->schools_id = $value->id;
                $insert_balace->people_id = 1;
                $insert_balace->year = date('Y');
                $insert_balace->subtraction = 0;
                $insert_balace->subtraction_temporary = 0;
                $insert_balace->code = $value->code_high_school;
                $insert_balace->type_balance = Balance::UTILES;
                $insert_balace->current = true;
                $insert_balace->disbursement_id = 1;
                $insert_balace->save();
                echo 'CODIGO PREPA: '.$insert_balace->code.' ESCUELA: '.$value->name.' MONTO Q: '.$insert_balace->balance.' UTILES'.PHP_EOL;

                $insert_balace = new Balance();
                $insert_balace->balance = random_int(5000,10000);
                $insert_balace->start_date = $fecha_p;
                $insert_balace->end_date = $fecha_f;
                $insert_balace->schools_id = $value->id;
                $insert_balace->people_id = 1;
                $insert_balace->year = date('Y');
                $insert_balace->subtraction = 0;
                $insert_balace->subtraction_temporary = 0;
                $insert_balace->code = $value->code_high_school;
                $insert_balace->type_balance = Balance::VALIJA_DIDACTICA;
                $insert_balace->current = true;
                $insert_balace->disbursement_id = 1;
                $insert_balace->save();
                echo 'CODIGO PREPA: '.$insert_balace->code.' ESCUELA: '.$value->name.' MONTO Q: '.$insert_balace->balance.' VALIJA_DIDACTICA'.PHP_EOL;
            }

            if(!is_null($value->code_primary)){
                $insert_balace = new Balance();
                $insert_balace->balance = random_int(50000,250000);
                $insert_balace->start_date = $fecha_p;
                $insert_balace->end_date = $fecha_f;
                $insert_balace->schools_id = $value->id;
                $insert_balace->people_id = 1;
                $insert_balace->year = date('Y');
                $insert_balace->subtraction = 0;
                $insert_balace->subtraction_temporary = 0;
                $insert_balace->code = $value->code_primary;
                $insert_balace->type_balance = Balance::ALIMENTACION;
                $insert_balace->current = true;
                $insert_balace->disbursement_id = 1;
                $insert_balace->save();
                echo 'CODIGO PRIMARIA: '.$insert_balace->code.' ESCUELA: '.$value->name.' MONTO Q: '.$insert_balace->balance.' ALIMENTACION'.PHP_EOL;

                $insert_balace = new Balance();
                $insert_balace->balance = random_int(50000,250000);
                $insert_balace->start_date = $fecha_p;
                $insert_balace->end_date = $fecha_f;
                $insert_balace->schools_id = $value->id;
                $insert_balace->people_id = 1;
                $insert_balace->year = date('Y');
                $insert_balace->subtraction = 0;
                $insert_balace->subtraction_temporary = 0;
                $insert_balace->code = $value->code_primary;
                $insert_balace->type_balance = Balance::GRATUIDAD;
                $insert_balace->current = true;
                $insert_balace->disbursement_id = 1;
                $insert_balace->save();
                echo 'CODIGO PRIMARIA: '.$insert_balace->code.' ESCUELA: '.$value->name.' MONTO Q: '.$insert_balace->balance.' GRATUIDAD'.PHP_EOL;

                $insert_balace = new Balance();
                $insert_balace->balance = random_int(50000,250000);
                $insert_balace->start_date = $fecha_p;
                $insert_balace->end_date = $fecha_f;
                $insert_balace->schools_id = $value->id;
                $insert_balace->people_id = 1;
                $insert_balace->year = date('Y');
                $insert_balace->subtraction = 0;
                $insert_balace->subtraction_temporary = 0;
                $insert_balace->code = $value->code_primary;
                $insert_balace->type_balance = Balance::UTILES;
                $insert_balace->current = true;
                $insert_balace->disbursement_id = 1;
                $insert_balace->save();
                echo 'CODIGO PRIMARIA: '.$insert_balace->code.' ESCUELA: '.$value->name.' MONTO Q: '.$insert_balace->balance.' UTILES'.PHP_EOL;

                $insert_balace = new Balance();
                $insert_balace->balance = random_int(10000,20000);
                $insert_balace->start_date = $fecha_p;
                $insert_balace->end_date = $fecha_f;
                $insert_balace->schools_id = $value->id;
                $insert_balace->people_id = 1;
                $insert_balace->year = date('Y');
                $insert_balace->subtraction = 0;
                $insert_balace->subtraction_temporary = 0;
                $insert_balace->code = $value->code_primary;
                $insert_balace->type_balance = Balance::VALIJA_DIDACTICA;
                $insert_balace->current = true;
                $insert_balace->disbursement_id = 1;
                $insert_balace->save();
                echo 'CODIGO PRIMARIA: '.$insert_balace->code.' ESCUELA: '.$value->name.' MONTO Q: '.$insert_balace->balance.' VALIJA_DIDACTICA'.PHP_EOL;
            }
        }

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


        for ($i=0; $i < 150; $i++) { 
            $insert_reservation = new Reservation();
            $insert_reservation->correlative = $i+1;
            $insert_reservation->year = date('Y');
            $insert_reservation->save();
        }

        //Generar compras aleatorias
        factory(CalendarSchool::class, 30)->create();
        factory(Order::class, 150)->create();
        factory(DetailOrder::class, 1000)->create();

        $detail_order = DetailOrder::all();

        foreach ($detail_order as $key => $value) {
            $insert = new ProgressOrder();
            $insert->purchased_amount = 0;
            $insert->order_statuses_id = 1;
            $insert->detail_orders_id = $value->id;
            $insert->products_id = $value->products_id;
            $insert->original_quantity = $value->quantity;
            $insert->save();
        }
    }
}

<?php

use App\Category;
use App\Models\Balance;
use App\Models\CalendarSchool;
use App\Models\DetailOrder;
use App\Models\DetailSuggestion;
use App\Models\LicensePlate;
use App\Models\MenuSuggestion;
use App\Models\Month;
use App\Models\Municipality;
use App\Models\Order;
use App\Models\PersonSchool;
use App\Models\Price;
use App\Models\Product;
use App\Models\ProductExpiration;
use App\Models\Provider;
use App\Models\Quantify;
use App\Models\Reservation;
use App\Models\School;
use App\Models\Vehicle;
use App\Models\VehicleModel;
use App\Models\Year;
use App\Seller;
use App\Transaction;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/*$factory->define(User::class, function (Faker $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => Str::random(10),
        'verified' => $verificado = $faker->randomElement([User::USUARIO_VERIFICADO, User::USUARIO_NO_VERIFICADO]),
        'verification_token' => $verificado == User::USUARIO_VERIFICADO ? null : User::generarVerificationToken(),
        'admin' => $faker->randomElement([User::USUARIO_ADMINISTRADOR, User::USUARIO_REGULAR])
    ];
});

$factory->define(Category::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->paragraph(1),
    ];
});

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->paragraph(1),
        'quantity' => $faker->numberBetween(1,50),
        'status' => $faker->randomElement([Product::PRODUCTO_DISPONIBLE, Product::PRODUCTO_NO_DISPONIBLE]),
        'image' => $faker->randomElement(['shampoo.png', 'jabon.png', 'pasta_dental.png']),
        'seller_id' => User::all()->random()->id
    ];
});

$factory->define(Transaction::class, function (Faker $faker) {

    $vendedor = Seller::has('products')->get()->random();
    $comprador = User::all()->except($vendedor->id)->random();
    return [
        'quantity' => $faker->numberBetween(1,3),
        'buyer_id' => $comprador->id,
        'product_id' => $vendedor->products->random()
    ];
});*/

$factory->define(Provider::class, function (Faker $faker) {
    $municipality = Municipality::all()->random();
    return [
        'nit' => rand(1000000, 99999999),
        'name' =>'proveedor '.$faker->numberBetween(1, 50),
        'direction'=>'calle '.$faker->numberBetween(1,25),
        'municipalities_id' => $municipality->id
    ];
});

$factory->define(CalendarSchool::class, function (Faker $faker) {
    $date_actual = Carbon::now();
    $date = $faker->dateTimeBetween($date_actual, '2021-01-01 00:00:00', null);
    $person_shool = PersonSchool::all()->random();

    return [
        'date' => $date->format('Y-m-d'),
        'schools_id' => $person_shool->schools_id,
        'people_id' => $person_shool->people_id,
        'title' => $faker->text(20)
    ];
});

$factory->define(Order::class, function (Faker $faker) {
    do {
        $vacion = false;
        $date_actual = Carbon::now();
        $person_shool = PersonSchool::all()->random();
        $school = School::find($person_shool->schools_id);
        $date = $faker->dateTimeBetween($date_actual, '2021-01-01 00:00:00', null);
        $year = Year::where('year',$date->format('Y'))->first();
        $month = Month::find($date->format('n'));

        $calendar = CalendarSchool::where('schools_id',$school->id)->get();

        foreach ($calendar as $key => $value) {
            if($value->date == $date->format('Y-m-d')) {
                $vacion = true;
            }
        }

    }while($vacion == true);

    return [
        'order' =>  $faker->unique()->numberBetween(1, 150),
        'title' => $faker->unique()->numerify('Menú ###'),
        'description' => $faker->text(100),
        'date' => $date->format('Y-m-d'),
        'total' => 0,
        'schools_id' => $person_shool->schools_id,
        'people_id' => $person_shool->people_id,
        'years_id' => $year->id,
        'months_id' => $month->id,
        'complete' => false,
        'type_order' => $faker->randomElement([Order::ALIMENTACION, Order::GRATUIDAD, Order::UTILES, Order::VALIJA_DIDACTICA]),
        'code' => $faker->randomElement([$school->code_high_school,$school->code_primary])
    ];
});

$factory->define(DetailOrder::class, function (Faker $faker) {

    do {
        $principal = 0;
        $resta = 1;
        $order = Order::all()->random();
        $balance = Balance::where('schools_id',$order->schools_id)->where('type_balance',$order->type_order)->where('code',$order->code)->where('current',true)->first();
        if(!is_null($balance))
        {
            $pro = $order->type_order;
            if($order->type_order == Order::VALIJA_DIDACTICA)
                $pro = Order::UTILES;

            $product = Product::where('propierty',$pro)->get()->random();
            $price = Price::where('products_id',$product->id)->where('current',true)->first();
            $cantidad = $faker->numberBetween(1,100);
            $date_actual = Carbon::now();
            $total = $cantidad * $price->price;
            $principal = $balance->balance;
            $balance->subtraction_temporary += $total;
            $resta = $balance->subtraction_temporary;
        }
    }while(($principal - $resta) < 0);

    $insert_quantify = Quantify::where('products_id',$product->id)->where('year',date('Y'))->first();


    for ($i=0; $i < $cantidad; $i++) { 
        if($product->stock_temporary > 0){
            $product->stock_temporary -= 1;

            $expiration = ProductExpiration::where('products_id',$product->id)->where('expiration',false)->where('current',false)->latest()->orderBy('date', 'asc')->first();
            if(!is_null($expiration))
            {
                $expiration->used += 1;

                if($expiration->quantity == $expiration->used)
                    $expiration->current = true;
    
                $expiration->save();
            }

        }else{
            $insert_quantify->subtraction += 1;
        }
    }
    $insert_quantify->sumary_schools +=  $cantidad;
    
    if($balance->balance == $balance->subtraction_temporary){
        $balance->current == true;
    }
    
    $order->total += $total;
    $order->balances_id = $balance->id;

    $insert_quantify->save();
    $product->save();
    $order->save();
    $balance->save();

    return [
        'quantity' => $cantidad,
        'sale_price' => $price->price,
        'subtotal' => $cantidad * $price->price,
        'observation' => $faker->randomElement(['', $faker->text(100)]),
        'complete' => false,
        'products_id' => $product->id,
        'orders_id' => $order->id
    ];
});

$factory->define(MenuSuggestion::class, function (Faker $faker) {
    return [
        'title' => $faker->unique()->numerify('Menú #####'),
        'description' => $faker->text(150),
        'people_id' => 1,
    ];
});

$factory->define(DetailSuggestion::class, function (Faker $faker) {
    return [
        'observation' => $faker->randomElement(['', $faker->text(100)]),
        'products_id' => Product::where('propierty','ALIMENTACION')->get()->random()->id,
        'menu_suggestions_id' => MenuSuggestion::all()->random()->id
    ];
});

$factory->define(Vehicle::class, function (Faker $faker) {
    $vin = '';
    $chasis = '';
    for ($i=0; $i < 13; $i++) { 
        $vin .= $faker->randomElement(['?','#']);
    }
    for ($i=0; $i < 13; $i++) { 
        $chasis .= $faker->randomElement(['?','#']);
    }
    return [
        'placa' => $faker->unique()->bothify('###???'),
        'color' => $faker->randomElement(['rojo','azul','amarillo','gris','negro','verde','celeste','beish','blaco']),
        'anio' => $faker->numberBetween(1990, date('Y')),
        'vin' => $faker->unique()->bothify($vin),
        'chasis' => $faker->unique()->bothify($chasis),
        'motor' => $faker->randomElement(['1000','1200','1400','1600','1800','2000','2200','2400','2600']),
        'license_plates_id' => LicensePlate::all()->random()->id,
        'vehicle_models_id' => VehicleModel::all()->random()->id
    ];
});
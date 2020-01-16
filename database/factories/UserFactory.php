<?php

use App\User;
use App\Seller;
use App\Category;
use App\Models\Balance;
use App\Models\CalendarSchool;
use App\Models\DetailOrder;
use App\Models\Year;
use App\Transaction;
use App\Models\Month;
use App\Models\Order;
use App\Models\School;
use App\Models\Reservation;
use Illuminate\Support\Str;
use App\Models\PersonSchool;
use App\Models\Price;
use App\Models\Product;
use App\Models\Quantify;
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;

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
    if(is_null($insert_quantify)) {
        $insert_quantify = new Quantify();
        $insert_quantify->year = $date_actual->format('Y');
        $insert_quantify->products_id = $product->products_id;
        
        $insert_quantify->sumary_schools =  $insert_quantify->sumary_schools + $cantidad;
        
        if($product->stock >= ($cantidad+$product->stock_temporary)){
            $insert_quantify->sumary_purchase += $cantidad;
        }

        $insert_quantify->subtraction = $insert_quantify->sumary_schools - $insert_quantify->sumary_purchase;

    } else {
        $insert_quantify->sumary_schools =  $insert_quantify->sumary_schools + $cantidad;
        
        if($product->stock >= ($cantidad+$product->stock_temporary)){
            $insert_quantify->sumary_purchase += $cantidad;
        }

        $insert_quantify->subtraction = $insert_quantify->sumary_schools - $insert_quantify->sumary_purchase;
    }

    if($balance->balance == $balance->subtraction_temporary){
        $balance->current == false;
    }
    
    $order->total += $total;

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
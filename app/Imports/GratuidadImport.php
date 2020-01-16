<?php

namespace App\Imports;

use App\Models\Price;
use App\Models\Product;
use App\Models\Category;
use App\Models\Quantify;
use App\Models\Presentation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class GratuidadImport implements ToCollection
{
    public function collection(Collection $collection)
    {
        foreach ($collection as $key => $value) {
            if($value[2] != '') {
                DB::beginTransaction();
                    $insert_presentacion = Presentation::where('name',mb_strtoupper($value[1]))->first();
                    if(is_null($insert_presentacion)){
                        $insert_presentacion = new Presentation();
                        $insert_presentacion->name = mb_strtoupper($value[0]);
                        $insert_presentacion->description = 'nueva marca';
                        $insert_presentacion->save();
                        echo '---- MARCA: '.$insert_presentacion->name.PHP_EOL;
                    }

                    $category = Category::select('id')->where('name',mb_strtoupper($value[0]))->first();
                    if(is_null(Product::where('name',mb_strtoupper($value[0]).', '.mb_strtoupper($value[2]))->first())){
                        $insert = new Product();
                        $insert->name = mb_strtoupper($value[0]).', '.mb_strtoupper($value[2]);
                        $insert->propierty = Product::GRATUIDAD;
                        $insert->categories_id = $category->id;
                        $insert->stock = random_int(1,100);
                        $insert->presentations_id = $insert_presentacion->id;
                        $insert->save();
    
                        if($value[3] == ''){
                            $value[3] = rand(1, 100)/10;
                        }
                        
                        $insert_price = new Price();
                        $insert_price->price = $value[3];
                        $insert_price->products_id = $insert->id;
                        $insert_price->save();
    
                        $insert_quantify = new Quantify();
                        $insert_quantify->sumary_schools = 0;
                        $insert_quantify->year = date('Y');
                        $insert_quantify->products_id = $insert->id;
                        $insert_quantify->save();   

                        echo 'PRODUCTO: '.$insert->name.' PRECIO: '.$insert_price->price.' PERTENECE: '.$insert->propierty.PHP_EOL;    
                    } 
                DB::commit();
            }
        }
    }
}

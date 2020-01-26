<?php

namespace App\Imports;

use App\Models\Price;
use App\Models\Product;
use App\Models\Category;
use App\Models\Quantify;
use App\Models\Presentation;
use App\Models\ProductExpiration;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class AlimentacionImport implements ToCollection
{
    public function collection(Collection $collection)
    {
        foreach ($collection as $key => $value) {
            if($value[2] != '') {
                DB::beginTransaction();
                    $insert_presentacion = Presentation::where('name',mb_strtoupper($value[1]))->first();
                    if(is_null($insert_presentacion)){
                        $insert_presentacion = new Presentation();
                        $insert_presentacion->name = mb_strtoupper($value[1]);
                        $insert_presentacion->description = 'nueva marca';
                        $insert_presentacion->save();
                        echo '---- MARCA: '.$insert_presentacion->name.PHP_EOL;
                    }

                    $category = Category::select('id')->where('name',mb_strtoupper($value[0]))->first();
                    if(is_null(Product::where('name',mb_strtoupper($value[0]).', '.mb_strtoupper($value[2]))->first())){
                        $insert = new Product();
                        $insert->name = mb_strtoupper($value[0]).', '.mb_strtoupper($value[2]);
                        $insert->propierty = Product::ALIMENTACION;
                        $insert->categories_id = $category->id;
                        $insert->stock = is_null($value[4]) ? 0 : random_int(1,100);
                        $insert->stock_temporary = $insert->stock;
                        $insert->presentations_id = $insert_presentacion->id;
                        $insert->persevering = is_null($value[4]) ? false : true;
                        $insert->save();
    
                        $insert_price = new Price();
                        $insert_price->price = $value[3];
                        $insert_price->products_id = $insert->id;
                        $insert_price->save();

                        if(!$insert->persevering)
                        {
                            for ($i=0; $i < 5; $i++) {  
                                $dias = random_int(50,365);                           
                                $insert_expirtation = new ProductExpiration();
                                $insert_expirtation->date = date("Y-m-d",strtotime(date("d-m-Y")."+ ".$dias." days")); 
                                $insert_expirtation->quantity = random_int(1,100);
                                $insert_expirtation->return = 0; 
                                $insert_expirtation->expiration = false;
                                $insert_expirtation->current = true;
                                $insert_expirtation->products_id = $insert->id;
                                $insert_expirtation->save();     
                                
                                if($insert_expirtation->date <= date("Y-m-d"))
                                {
                                    $insert_expirtation->expiration = true;
                                    $insert_expirtation->return = $insert_expirtation->quantity;
                                }
                                else
                                {
                                    $insert->stock += $insert_expirtation->quantity;
                                    $insert->stock_temporary = $insert->stock;
                                    $insert_expirtation->used = $insert_expirtation->quantity;
                                }

                                $insert_expirtation->save(); 
                                $insert->save(); 
                            }                            
                        }

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

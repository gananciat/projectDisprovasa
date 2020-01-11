<?php

namespace App\Imports;

use App\Models\Menu;
use App\Models\MenuRol;
use App\Models\Rol;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class MenuImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $administrador = Rol::where('name','administrador')->first();
        $gerente = Rol::where('name','gerente')->first();
        $compra = Rol::where('name','compra')->first();

        foreach ($collection as $key => $value) {
            if($value[0] != ''){
                DB::beginTransaction();

                    $insert = new Menu();
                    $insert->name = $value[0];
                    $insert->route = $value[1];
                    $insert->route_name = $value[2];
                    $insert->father = $value[3];
                    $insert->icon = $value[4];
                    $insert->hide = $value[5];

                    $insert->save();

                    $insert_menu_rol = new MenuRol();
                    $insert_menu_rol->rols_id = $administrador->id;
                    $insert_menu_rol->menus_id = $insert->id;
                    $insert_menu_rol->save();

                    echo $insert->name.' ->' .'GERENTE: ',$value[7].' COMPRA:'.$value[8].PHP_EOL;

                    if((int)$value[7] == 1){
                    	$insert_menu_rol = new MenuRol();
	                    $insert_menu_rol->rols_id = $gerente->id;
	                    $insert_menu_rol->menus_id = $insert->id;
	                    $insert_menu_rol->save();
                    }

                    if((int)$value[8] == 1){
                    	$insert_menu_rol = new MenuRol();
	                    $insert_menu_rol->rols_id = $compra->id;
	                    $insert_menu_rol->menus_id = $insert->id;
	                    $insert_menu_rol->save();
                    }


                DB::commit();
            }
        }
    }
}

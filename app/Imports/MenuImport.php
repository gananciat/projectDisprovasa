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
        $administrador = Rol::where('name',Rol::ADMINISTRADOR)->first();
        $gerente = Rol::where('name',Rol::GERENTE)->first();
        $bodega = Rol::where('name',Rol::BODEGA)->first();
        $supervisor = Rol::where('name',Rol::SUPERVISOR)->first();
        $compra = Rol::where('name',Rol::COMPRA)->first();
        $facturador = Rol::where('name',Rol::FACTURADOR)->first();
        $repartidor = Rol::where('name',Rol::REPARTIDOR)->first();

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

                    if(!is_null($value[6]))
                    {
                        $insert_menu_rol = new MenuRol();
                        $insert_menu_rol->rols_id = $administrador->id;
                        $insert_menu_rol->menus_id = $insert->id;
                        $insert_menu_rol->save();

                        echo "ROL -- {$administrador->name} || MENU -- {$insert->name}".PHP_EOL;
                    }

                    if(!is_null($value[7]))
                    {
                        $insert_menu_rol = new MenuRol();
                        $insert_menu_rol->rols_id = $gerente->id;
                        $insert_menu_rol->menus_id = $insert->id;
                        $insert_menu_rol->save();

                        echo "ROL -- {$gerente->name} || MENU -- {$insert->name}".PHP_EOL;
                    }

                    if(!is_null($value[8]))
                    {
                        $insert_menu_rol = new MenuRol();
                        $insert_menu_rol->rols_id = $bodega->id;
                        $insert_menu_rol->menus_id = $insert->id;
                        $insert_menu_rol->save();

                        echo "ROL -- {$bodega->name} || MENU -- {$insert->name}".PHP_EOL;
                    }

                    if(!is_null($value[9]))
                    {
                        $insert_menu_rol = new MenuRol();
                        $insert_menu_rol->rols_id = $supervisor->id;
                        $insert_menu_rol->menus_id = $insert->id;
                        $insert_menu_rol->save();

                        echo "ROL -- {$supervisor->name} || MENU -- {$insert->name}".PHP_EOL;
                    }

                    if(!is_null($value[10]))
                    {
                        $insert_menu_rol = new MenuRol();
                        $insert_menu_rol->rols_id = $compra->id;
                        $insert_menu_rol->menus_id = $insert->id;
                        $insert_menu_rol->save();

                        echo "ROL -- {$compra->name} || MENU -- {$insert->name}".PHP_EOL;
                    }

                    if(!is_null($value[11]))
                    {
                        $insert_menu_rol = new MenuRol();
                        $insert_menu_rol->rols_id = $facturador->id;
                        $insert_menu_rol->menus_id = $insert->id;
                        $insert_menu_rol->save();

                        echo "ROL -- {$facturador->name} || MENU -- {$insert->name}".PHP_EOL;
                    }

                    if(!is_null($value[12]))
                    {
                        $insert_menu_rol = new MenuRol();
                        $insert_menu_rol->rols_id = $repartidor->id;
                        $insert_menu_rol->menus_id = $insert->id;
                        $insert_menu_rol->save();

                        echo "ROL -- {$repartidor->name} || MENU -- {$insert->name}".PHP_EOL;
                    }

                DB::commit();
            }
        }
    }
}

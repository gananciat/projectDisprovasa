<?php

namespace App\Imports;

use App\Models\Presentation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class MarcaImport implements ToCollection
{
    public function collection(Collection $collection)
    {
        foreach ($collection as $key => $value) {
            if($value[0] != '' || $value[0] != null){
                if(is_null(Presentation::where('name',mb_strtoupper($value[0]))->first())){
                    $insert_presentacion = new Presentation();
                    $insert_presentacion->name = mb_strtoupper($value[0]);
                    $insert_presentacion->description = 'nueva marca';
                    $insert_presentacion->save();
                    echo '---- MARCA: '.$insert_presentacion->name.PHP_EOL;
                }
            }
        }
    }
}

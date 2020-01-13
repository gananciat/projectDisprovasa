<?php

namespace App\Imports;

use App\Models\Municipality;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class MunicipioImport implements ToCollection
{
    public function collection(Collection $collection)
    {
        foreach ($collection as $key => $value) {
            if($value[0] != ''){
                $insert = new Municipality();
                $insert->name = mb_strtoupper($value[0]);
                $insert->departaments_id = $value[1];
                $insert->save();
                echo 'MUNICIPIO: '.$insert->name.PHP_EOL;
            }
        }
    }
}

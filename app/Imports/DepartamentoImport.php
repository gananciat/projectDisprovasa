<?php

namespace App\Imports;

use App\Models\Departament;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class DepartamentoImport implements ToCollection
{
    public function collection(Collection $collection)
    {
        foreach ($collection as $key => $value) {
            if($value[0] != '' && is_null(Departament::where('name',mb_strtoupper($value[0]))->first())){
                $insert = new Departament();
                $insert->name = mb_strtoupper($value[0]);
                $insert->save();
                echo 'DEPARTAMENTO: '.$insert->name.PHP_EOL;
            }
        }
    }
}

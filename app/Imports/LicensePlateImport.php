<?php

namespace App\Imports;

use App\Models\LicensePlate;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class LicensePlateImport implements ToCollection
{
    public function collection(Collection $collection)
    {
        foreach ($collection as $key => $value) {
            if(!is_null($value[0]))
            {
                $insert = new LicensePlate();
                $insert->name = $value[0];
                $insert->type = $value[1];
                $insert->save();
                echo 'TIPO DE PLACA: '.$insert->name.PHP_EOL;
            }
        }
    }
}

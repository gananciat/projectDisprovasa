<?php

namespace App\Imports;

use App\Models\VehicleBrand;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class VehicleBrandImport implements ToCollection
{
    public function collection(Collection $collection)
    {
        foreach ($collection as $key => $value) {
            if(!is_null($value[0]))
            {
                $insert = new VehicleBrand();
                $insert->name = $value[0];
                $insert->save();
                echo 'MARCA DE VEHICULO: '.$insert->name.PHP_EOL;
            }
        }
    }
}

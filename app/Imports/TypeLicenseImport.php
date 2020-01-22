<?php

namespace App\Imports;

use App\Models\TypeLicense;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class TypeLicenseImport implements ToCollection
{
    public function collection(Collection $collection)
    {
        foreach ($collection as $key => $value) {
            if(!is_null($value[0]))
            {
                $insert = new TypeLicense();
                $insert->name = $value[0];
                $insert->type = $value[1];
                $insert->save();
                echo 'TIPO DE LICENCIA: '.$insert->type.PHP_EOL;
            }
        }
    }
}

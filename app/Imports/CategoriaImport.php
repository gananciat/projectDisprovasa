<?php

namespace App\Imports;

use App\Models\Category;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class CategoriaImport implements ToCollection
{
    public function collection(Collection $collection)
    {
        foreach ($collection as $key => $value) {
            if($value[0] != ''){
                if(is_null(Category::where('name',mb_strtoupper($value[0]))->first())){
                    $insert = new Category();
                    $insert->name = mb_strtoupper($value[0]);
                    $insert->description = 'nuevo categoría';
                    $insert->save();
                    echo 'CATEGORIA: '.$insert->name.PHP_EOL;
                }
            }
        }
    }
}

<?php

namespace App\Imports;

use App\Models\VehicleBrand;
use App\Models\VehicleModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class VehicleModelImport implements ToCollection
{
    public function collection(Collection $collection)
    {
        foreach ($collection as $key => $value) {
            if(!is_null($value[0]) && is_null(VehicleModel::where('name',$value[1])->first()))
            {
                $brand = VehicleBrand::find($value[0]);
                $insert = new VehicleModel();
                $insert->name = $value[1];
                $insert->brand_model = $brand->name.' '.$insert->name;
                $insert->vehicle_brands_id = $brand->id;
                $insert->save();
                echo 'MODELO DE VEHICULO: '.$insert->brand_model.PHP_EOL;
            }
        }
    }
}

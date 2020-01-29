<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\ApiController;
use App\Models\Vat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VatController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $vats = Vat::all();
        return $this->showAll($vats);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
            $rules = [
                'value' => 'required|integer',
            ];
        
            $this->validate($request, $rules);
            $data = $request->all();
            $actives = Vat::where('actual',true)->get();
            foreach ($actives as $a) {
                $a->actual = false;
                $a->save();
            }
            $vat = Vat::create($data);
        DB::commit();
        return $this->showOne($vat,201);
    }

    public function show(Vat $vat)
    {
        return $this->showOne($vat);
    }

    public function update(Request $request, Vat $vat)
    {
        $rules = [
            'value' => 'integer',
        ];

        $this->validate($request, $rules);
        DB::beginTransaction();
            $vat->value = $request->value;

            if($request->actual){
                $actives = Vat::where('actual',true)->get();
                foreach ($actives as $a) {
                    $a->actual = false;
                    $a->save();
                }
            }
            $vat->actual = $request->actual;

            if (!$vat->isDirty()) {
                return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
            }

            $vat->save();
        DB::commit();
        return $this->showOne($vat,201);
    }

    public function destroy(Vat $vat)
    {
        if(count($vat->invoices)) {
            return $this->errorResponse('No se puede eliminar iva porque ya contiene facturas con este iva', 422);
        }

        $vat->delete();

        return $this->showOne($vat,201);
    }
}

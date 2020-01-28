<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\ApiController;
use App\Models\Serie;
use Illuminate\Http\Request;

class SerieController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $series = Serie::all();
        return $this->showAll($series);
    }

    public function store(Request $request)
    {
        $rules = [
            'serie' => 'required|unique:series,serie',
            'total' => 'required|integer',
            'init' => 'required|integer',
            'expiration_date' => 'required'
        ];
        
        $this->validate($request, $rules);
        $data = $request->all();
        $data['actual_bill'] = $request->init - 1;
        $active = Serie::where('active',true)->first();

        if(!is_null($active)){
            return $this->errorResponse('no puede agregar una nueva serie aun cuenta con una serie activa',422);
        }

        $serie = serie::create($data);

        return $this->showOne($serie,201);
    }

    public function show(serie $serie)
    {
        return $this->showOne($serie);
    }

    public function update(Request $request, Serie $serie)
    {
        $rules = [
            'serie' => 'required|unique:series,serie,'.$serie->serie,
            'total' => 'required|integer',
            'init' => 'required|integer',
            'expiration_date' => 'required'
        ];

        $this->validate($request, $rules);

        $serie->serie = $request->serie;
        $serie->total = $request->total;
        $serie->init = $request->init;
        $serie->actual = $request->init - 1;
        $serie->expiration_date = $request->expiration_date;

        if (!$serie->isDirty()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $serie->save();
        return $this->showOne($serie,201);
    }

    public function destroy(Serie $serie)
    {
        if (count($serie->invoices)) {
            return $this->errorResponse('No se puede eliminar serie porque ya contiene facturas asociadas', 422);
        }

        $serie->delete();
        
        return $this->showOne($serie,201);
    }
}

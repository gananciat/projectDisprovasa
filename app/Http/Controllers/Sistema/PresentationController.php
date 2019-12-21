<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Presentation;
use Illuminate\Http\Request;

class PresentationController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->showAll(Presentation::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:75|unique:presentations,name',
            'description' => 'nullable|string|max:250'
        ];
        
        $this->validate($request, $rules);
        $data = $request->all();
        $insert = Presentation::create($data);

        return $this->showOne($insert,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Presentation  $presentation
     * @return \Illuminate\Http\Response
     */
    public function show(Presentation $presentation)
    {
        return $this->showOne($presentation);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Presentation  $presentation
     * @return \Illuminate\Http\Response
     */
    public function edit(Presentation $presentation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Presentation  $presentation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Presentation $presentation)
    {
        $rules = [
            'name' => 'required|string|max:75|unique:presentations,name,' . $presentation->id,
            'description' => 'nullable|string|max:250'
        ];

        $this->validate($request, $rules);

        $presentation->name = $request->name;
        $presentation->description = $request->description;

         if (!$presentation->isDirty()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $presentation->save();
        return $this->showOne($presentation);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Presentation  $presentation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Presentation $presentation)
    {
        $presentation->delete();
        return $this->showOne($presentation);
    }
}

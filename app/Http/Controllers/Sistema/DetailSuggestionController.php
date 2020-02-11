<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\DetailSuggestion;
use Illuminate\Http\Request;

class DetailSuggestionController extends ApiController
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = [
            'product_id.exists'    => 'Debe de seleccionar la menos un producto.',
            'menu_suggestions_id.exists'    => 'Debe de seleccionar la menos un menú.',
        ];

        $rules = [
            'product_id' => 'required|integer|exists:products,id',
            'menu_suggestions_id' => 'required|integer|exists:menu_suggestions,id',
            'observation' => 'max:1000',
        ];
        
        $this->validate($request, $rules, $messages);

        if(!is_null(DetailSuggestion::where('products_id',$request->product_id)->where('menu_suggestions_id',$request->menu_suggestions_id)->first()))
            return $this->errorResponse('El producto ya fue agregado al menú', 422);

        $insert_detail = new DetailSuggestion();
        $insert_detail->observation = $request->observation;
        $insert_detail->products_id = $request->product_id;
        $insert_detail->menu_suggestions_id = $request->menu_suggestions_id;
        $insert_detail->save();

        return $this->showOne($insert_detail, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DetailSuggestion  $detailSuggestion
     * @return \Illuminate\Http\Response
     */
    public function show(DetailSuggestion $detail_suggestion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DetailSuggestion  $detailSuggestion
     * @return \Illuminate\Http\Response
     */
    public function edit(DetailSuggestion $detail_suggestion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DetailSuggestion  $detailSuggestion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DetailSuggestion $detail_suggestion)
    {
        $messages = [
            'product_id.exists'    => 'Debe de seleccionar la menos un producto.',
            'menu_suggestions_id.exists'    => 'Debe de seleccionar la menos un menú.',
        ];

        $rules = [
            'product_id' => 'required|integer|exists:products,id',
            'menu_suggestions_id' => 'required|integer|exists:menu_suggestions,id',
            'observation' => 'max:1000',
        ];
        
        $this->validate($request, $rules, $messages);

        if($request->observation == $detail_suggestion->observation)
        {
            if(!is_null(DetailSuggestion::where('products_id',$request->product_id)->where('menu_suggestions_id',$request->menu_suggestions_id)->first()))
                return $this->errorResponse('El producto ya fue agregado al menú', 422);
        }

        $detail_suggestion->observation = $request->observation;
        $detail_suggestion->products_id = $request->product_id;
        $detail_suggestion->menu_suggestions_id = $request->menu_suggestions_id;

        if (!$detail_suggestion->isDirty()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $detail_suggestion->save();

        return $this->showOne($detail_suggestion, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DetailSuggestion  $detailSuggestion
     * @return \Illuminate\Http\Response
     */
    public function destroy(DetailSuggestion $detail_suggestion)
    {
        $detail_suggestion->current = false;
        $detail_suggestion->save();
        return $this->showOne($detail_suggestion, 201);
    }
}

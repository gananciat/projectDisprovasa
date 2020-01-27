<?php

namespace App\Http\Controllers\Sistema;

use Illuminate\Http\Request;
use App\Models\MenuSuggestion;
use App\Models\DetailSuggestion;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;

class MenuSuggestionController extends ApiController
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
        $menus = MenuSuggestion::with(['details.product.presentation',
                                        'details.product.prices' => function ($query) {
                                            $query->where('current', true);
                                        }
                                    ]
                                    )->get();

        return $this->showAll($menus);
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
            'title.unique'    => 'El título del menú debe ser único.',
        ];

        $rules = [
            'title' => 'required|string|max:125|unique:orders,title',
            'description' => 'required|string|max:1000',
        ];
        
        $this->validate($request, $rules, $messages);

        try {

            DB::beginTransaction();

            $insert = new MenuSuggestion();
            $insert->title = $request->title;
            $insert->description = $request->description;
            $insert->people_id = Auth::user()->people_id;  
            $insert->save();

            for ($i=0; $i < count($request->detail_order); $i++) { 
                $insert_detail = new DetailSuggestion();
                $insert_detail->observation = $request->detail_order[$i]['observation'];
                $insert_detail->products_id = $request->detail_order[$i]['products_id'];
                $insert_detail->menu_suggestions_id = $insert->id;
                $insert_detail->save();
            }

            DB::commit();

            return $this->showOne($insert, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(),403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MenuSuggestion  $menuSuggestion
     * @return \Illuminate\Http\Response
     */
    public function show(MenuSuggestion $menu_suggestion)
    {
        $menus = MenuSuggestion::with(['details.product.presentation',
                                        'details.product.prices' => function ($query) {
                                            $query->where('current', true);
                                        }
                                    ]
                                    )
                                ->where('id', $menu_suggestion->id)
                                ->get();

        return $this->showAll($menus);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MenuSuggestion  $menuSuggestion
     * @return \Illuminate\Http\Response
     */
    public function edit(MenuSuggestion $menuSuggestion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MenuSuggestion  $menuSuggestion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MenuSuggestion $menu_suggestion)
    {
        $messages = [
            'title.unique'    => 'El título del menú debe ser único.',
        ];

        $rules = [
            'title' => 'required|string|max:125|unique:orders,title,'.$menu_suggestion->id,
            'description' => 'required|string|max:200',
            'date' => 'required|date',
        ];
        
        $this->validate($request, $rules, $messages);

        $menu_suggestion->title = $request->title;
        $menu_suggestion->description = $request->description;
        $menu_suggestion->people_id = Auth::user()->people_id;  

        if (!$menu_suggestion->isDirty()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $menu_suggestion->save();

        return $this->showOne($menu_suggestion, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MenuSuggestion  $menuSuggestion
     * @return \Illuminate\Http\Response
     */
    public function destroy(MenuSuggestion $menu_suggestion)
    {
        try {

            DB::beginTransaction();

                $detalle = DetailSuggestion::where('menu_suggestions_id','=',$menu_suggestion->id)->get();

                foreach ($detalle as $key => $value) {
                    $value->delete();
                }

            $menu_suggestion->delete();

            DB::commit();

            return $this->showOne($menu_suggestion, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(),403);
        } 
    }
}

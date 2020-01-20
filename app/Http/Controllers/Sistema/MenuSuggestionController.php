<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\MenuSuggestion;
use Illuminate\Http\Request;

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
        //
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
    public function update(Request $request, MenuSuggestion $menuSuggestion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MenuSuggestion  $menuSuggestion
     * @return \Illuminate\Http\Response
     */
    public function destroy(MenuSuggestion $menuSuggestion)
    {
        //
    }
}

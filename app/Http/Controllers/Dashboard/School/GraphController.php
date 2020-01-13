<?php

namespace App\Http\Controllers\Dashboard\School;

use App\Http\Controllers\ApiController;
use App\Models\School;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PersonSchool;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class GraphController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function school_order()
    {
        if(Auth::user()->current_school == true)
        {
            $school_order = School::select('id','name')
                                                    ->withCount([
                                                                    'orders as order_complete_a' => function(Builder $query) {
                                                                                                                            $query->where([
                                                                                                                                            ['complete', true],
                                                                                                                                            ['type_order', Order::ALIMENTACION]
                                                                                                                                        ]);
                                                                                                                        },
                                                                    'orders as order_incomplete_a' => function(Builder $query) {
                                                                                                                            $query->where([
                                                                                                                                            ['complete', false],
                                                                                                                                            ['type_order', Order::ALIMENTACION]
                                                                                                                                        ]);
                                                                                                                        },      
                                                                    'orders as order_complete_g' => function(Builder $query) {
                                                                                                                            $query->where([
                                                                                                                                            ['complete', true],
                                                                                                                                            ['type_order', Order::GRATUIDAD]
                                                                                                                                        ]);
                                                                                                                        },
                                                                    'orders as order_incomplete_g' => function(Builder $query) {
                                                                                                                            $query->where([
                                                                                                                                            ['complete', false],
                                                                                                                                            ['type_order', Order::GRATUIDAD]
                                                                                                                                        ]);
                                                                                                                        },        
                                                                    'orders as order_complete_u' => function(Builder $query) {
                                                                                                                            $query->where([
                                                                                                                                            ['complete', true],
                                                                                                                                            ['type_order', Order::UTILES]
                                                                                                                                        ]);
                                                                                                                        },
                                                                    'orders as order_incomplete_u' => function(Builder $query) {
                                                                                                                            $query->where([
                                                                                                                                            ['complete', false],
                                                                                                                                            ['type_order', Order::UTILES]
                                                                                                                                        ]);
                                                                                                                        },                                                                                                                                                                                                                   
                                                                ])
                                                    ->where('id', PersonSchool::where('people_id', Auth::user()->people_id)->where('current', true)->first()->schools_id)
                                                    ->get(); 

            return $this->showAll($school_order);
        }
        else
        {
            $school_order = School::select('id','name','logo')
                                                    ->withCount([
                                                                    'orders as order_complete_a' => function(Builder $query) {
                                                                                                                            $query->where([
                                                                                                                                            ['complete', true],
                                                                                                                                            ['type_order', Order::ALIMENTACION]
                                                                                                                                        ]);
                                                                                                                        },
                                                                    'orders as order_incomplete_a' => function(Builder $query) {
                                                                                                                            $query->where([
                                                                                                                                            ['complete', false],
                                                                                                                                            ['type_order', Order::ALIMENTACION]
                                                                                                                                        ]);
                                                                                                                        },      
                                                                    'orders as order_complete_g' => function(Builder $query) {
                                                                                                                            $query->where([
                                                                                                                                            ['complete', true],
                                                                                                                                            ['type_order', Order::GRATUIDAD]
                                                                                                                                        ]);
                                                                                                                        },
                                                                    'orders as order_incomplete_g' => function(Builder $query) {
                                                                                                                            $query->where([
                                                                                                                                            ['complete', false],
                                                                                                                                            ['type_order', Order::GRATUIDAD]
                                                                                                                                        ]);
                                                                                                                        },        
                                                                    'orders as order_complete_u' => function(Builder $query) {
                                                                                                                            $query->where([
                                                                                                                                            ['complete', true],
                                                                                                                                            ['type_order', Order::UTILES]
                                                                                                                                        ]);
                                                                                                                        },
                                                                    'orders as order_incomplete_u' => function(Builder $query) {
                                                                                                                            $query->where([
                                                                                                                                            ['complete', false],
                                                                                                                                            ['type_order', Order::UTILES]
                                                                                                                                        ]);
                                                                                                                        },                                                                                                                                                                                                                   
                                                                ])
                                                    ->paginate(10);      

            return $this->successResponse($school_order);
        }
    }
}

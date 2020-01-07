<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Balance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BalanceController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $balances = Balance::all();
        return $this->showAll($balances);
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
            'items' => 'required'
        ];
        
        $this->validate($request, $rules);

        DB::beginTransaction();
            $items = $request->items;
            foreach ($items as $item) {
                $rules2 = [
                    'balance'=>'required',
                    'start_date'=>'required|date',
                    'end_date'=>'required|date',
                    'schools_id'=>'required|integer|exists:schools,id',
                    'year'=>'required',
                    'code',
                    'type_balance',
                    'current',
                    'disbursement_id'=>'required|integer|exists:disbursement,id'
                ];

                $this->validate($item, $rules2);

                $balance = Balance::create($item);
            }
        DB::commit();
        return $this->showOne($items,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Balance  $balance
     * @return \Illuminate\Http\Response
     */
    public function show(Balance $balance)
    {
        
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Balance  $balance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Balance $balance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Balance  $balance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Balance $balance)
    {
        //
    }
}

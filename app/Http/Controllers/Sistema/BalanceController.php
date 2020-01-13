<?php

namespace App\Http\Controllers\Sistema;

use Carbon\Carbon;
use App\Models\Balance;
use App\Models\Disbursement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

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

    public function getDisbursement($items)
    {
        $disbursement = Disbursement::whereNotNull('id')->first();
        if(count($items)){
            $last_id = $items->last()->disbursement_id;
            $disbursement = Disbursement::where('id','>',$last_id)->orderBy('id')->first();   
        }
        return $disbursement;
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
                foreach ($item['items'] as $item2) {
                    $year = Carbon::parse($item2['start_date'])->year;

                    $balances = Balance::where('year',$year)->where('schools_id',$item['school_id'])->where('type_balance',$item2['type_balance'])->where('code',$item['code'])->get();
                    
                    $disbursement = $this->getDisbursement($balances);
                    if(is_null($disbursement)){
                        return $this->errorResponse('ya no existen desembolsos registrados en la base de datos',421);
                    }

                    $item2['year'] = $year;
                    $item2['schools_id'] = $item['school_id'];
                    $item2['disbursement_id'] = $disbursement->id;
                    $item2['people_id'] = $request->user()->people_id;
                    $item2['code'] = $item['code']; 

                    $request2 = new Request($item2);

                    $rules2 = [
                        'balance'=>'required',
                        'start_date'=>'required|date',
                        'end_date'=>'required|date',
                        'schools_id'=>'required|integer|exists:schools,id',
                        'year'=>'required',
                        'code'=>'required',
                        'type_balance'=>'required',
                        'disbursement_id'=>'required|integer|exists:disbursement,id'
                    ];

                    $this->validate($request2, $rules2);
                    $balance = Balance::create($item2);
                }

            }
        DB::commit();
        return response()->json(['message' => 
            'registros guardados con exito'],200);
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
        $rules = [
            'start_date' => 'required',
            'end_date' => 'required',
            'balance' => 'required'
        ];

        $this->validate($request, $rules);

        $balance->start_date = $request->start_date;
        $balance->end_date = $request->end_date;
        $balance->balance = $request->balance;

        if (!$balance->isDirty()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $balance->save();
        return $this->showOne($balance,201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Balance  $balance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Balance $balance)
    {
        $balance->delete();
        return $this->showOne($balance,201);
    }
}

<?php

namespace App\Http\Controllers\Dashboard\Sistema;

use Carbon\Carbon;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GraphController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function purchasesLastGraph()
    {
    	$init = Carbon::now();
    	$finish = Carbon::now()->endOfMonth();

    	$init->modify('-12 months')->firstOfMonth();

        $purchases = DB::table('purcharses')
		    ->select(
		        DB::raw('YEAR(date) as year'),
		        DB::raw('MONTH(date) as month'),
		        DB::raw('SUM(total) as sum')
		    )
		    ->where('date','>=',$init)
		    ->where('date','<=',$finish)
		    ->groupBy('year', 'month')
		    ->get();

		return $this->showQuery($purchases);
    }
}

<?php

namespace App\Http\Controllers\Sistema;

use Carbon\Carbon;
use App\Http\Controllers\ApiController;
use App\Models\ProductExpiration;
use Illuminate\Http\Request;

class ProductExpirationController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $date = Carbon::now()->subMonths(2)->format('Y-m-d');
        $actual_date = Carbon::now()->format('Y-m-d');

        $products = ProductExpiration::where('date','>=',$date)->where('date','<=',$actual_date)->with('product')->get();

        foreach ($products as $p) {
            //$date_sum = Carbon::parse($p->date)->addMonths(2)->format('Y-m-d');
            if($actual_date >=$p->date){
                $p->expiration = true;
                $p->return = $p->quantity - $p->used;
                $p->save();
            }    
        }

        return $this->showAll($products);
    }

    public function store(Request $request)
    {
        //
    }

    public function show(ProductExpiration $productExpiration)
    {
        //
    }

    public function destroy(ProductExpiration $productExpiration)
    {
        //
    }
}
    
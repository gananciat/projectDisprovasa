<?php

namespace App\Http\Controllers\Reports;

use App\Models\DetailOrder;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderReportControler extends ApiController
{
    public function __construct()
    {
        //parent::__construct();
    }

    public function ProductsOrderedByDates($start_date, $end_date)
    {
       /* $orders = DB::table('detail_orders')
            ->join('products', 'products.id', '=', 'detail_orders.products_id')
            ->join('orders', 'orders.id', '=', 'detail_orders.orders_id')
            ->select(
                DB::raw('products_id'),
                DB::raw('products.name as product'),
                DB::raw('SUM(sale_price) as sum'),
                DB::raw('COUNT(products_id) as total')
            )
            ->groupBy('products_id')
            ->get();*/

         $orders = DB::table('detail_orders')
                            ->join('progress_orders','progress_orders.detail_orders_id','=','detail_orders.id')
                            ->join('orders', 'detail_orders.orders_id', '=', 'orders.id')
                            ->join('products','detail_orders.products_id','=','products.id')
                            ->select('products.name as product','products.id', 
                                DB::raw('SUM(quantity) as original_quantity'),
                                DB::raw('SUM(progress_orders.purchased_amount) as quantity'))
                            ->where('orders.date','>=',$start_date)
                            ->where('orders.date','<=',$end_date)
                            ->orderBy('original_quantity','desc')
                            ->groupBy('product')
                            ->get();

        return $this->showQuery($orders);
    }
}

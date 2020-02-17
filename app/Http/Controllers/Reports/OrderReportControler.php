<?php

namespace App\Http\Controllers\Reports;

use App\Exports\QuantityProductOrdersExport;
use App\Http\Controllers\ApiController;
use App\Models\DetailOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class OrderReportControler extends ApiController
{
    public function __construct()
    {
        //parent::__construct();
    }

    public function ProductsOrderedByDates($start_date, $end_date)
    {
        $orders = $this->getDataByDate($start_date, $end_date);

        return $this->showQuery($orders);
    }

    public function exportExcel($start_date, $end_date)
    {
        $orders = $this->getDataByDate($start_date, $end_date);
        $description = 'REPORTE DE PRODUCTOS PEDIDOS '.$this->getDates($start_date,$end_date);

        return Excel::download(new QuantityProductOrdersExport($orders,$description), 'products.xlsx');
    }

    public function getDataByDate($start_date, $end_date)
    {
        $orders = DB::table('detail_orders')
                            ->join('progress_orders','progress_orders.detail_orders_id','=','detail_orders.id')
                            ->join('orders', 'detail_orders.orders_id', '=', 'orders.id')
                            ->join('products','detail_orders.products_id','=','products.id')
                            ->select('products.name as product', 
                                DB::raw('SUM(quantity) as original_quantity'),
                                DB::raw('SUM(progress_orders.purchased_amount) as quantity'))
                            ->where('orders.date','>=',$start_date)
                            ->where('orders.date','<=',$end_date)
                            ->orderBy('original_quantity','desc')
                            ->groupBy('product')
                            ->get();

        return $orders;
    }

    public function getDates($start_date, $end_date)
    {
        if(!is_null($start_date) && !is_null($end_date)){
            return 'DE '. date("d/m/Y", strtotime($start_date)). ' A '. date("d/m/Y", strtotime($end_date));
        }
        return '';
    }
}

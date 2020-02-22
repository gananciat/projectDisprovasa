<?php

namespace App\Http\Controllers\Reports;

use App\Models\Product;
use App\Models\DetailOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductProviderExport;
use App\Http\Controllers\ApiController;
use App\Exports\QuantityProductOrdersExport;

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

    public function exportProductProvider($id = 0)
    {
        $products = Product::with('category')->get();
        $arr_export = $this->mapDataProducts($products);

        return Excel::download(new ProductProviderExport($arr_export), 'products_providers.xlsx');
    }

    public function mapDataProducts($products)
    {
        $arr_export = [];
        $heading = ['proveedor','cantidad','merma','total (Q)'];

        foreach ($products as $p) {
            array_push($arr_export,[$p->name]);
            array_push($arr_export,$heading);
            $detail = DB::table('purcharse_details')
                        ->join('purcharses','purcharse_details.purcharse_id','purcharses.id')
                        ->join('provider','purcharses.provider_id','provider.id')
                        ->select('provider.name as provider_name',
                            DB::raw('sum(purcharse_details.quantity) as cantidad'),
                            DB::raw('sum(purcharse_details.decrease) as merma'),
                            DB::raw('sum(purcharse_details.purcharse_price * purcharse_details.quantity) as total'))
                        ->where('purcharse_details.product_id',$p->id)
                        ->orderBy('cantidad','DESC')
                        ->groupBy('provider_name')
                        ->get();

            foreach ($detail as $d) {
                array_push($arr_export, [$d->provider_name,$d->cantidad,$d->merma,number_format(($d->total),2)]);
            }
            array_push($arr_export, ['']);
        }

        return $arr_export;
    }
}

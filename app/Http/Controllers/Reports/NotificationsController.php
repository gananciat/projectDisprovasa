<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationsController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        $low_products = $products->where('stock','<',5)->get();
        $orders = Order::where('complete',false)->get();

        return response()->json([
            'low_products' => $low_products,
            'orders' => $orders
        ]);
    }
}

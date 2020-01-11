<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class NotificationsController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $products = Product::all();
        $low_products = $products->where('stock','<',5)->sortBy('stock')->values();
        $orders = Order::where('complete',false)->get();

        return response()->json([
            'low_products' => $low_products,
            'orders' => $orders
        ]);
    }
}

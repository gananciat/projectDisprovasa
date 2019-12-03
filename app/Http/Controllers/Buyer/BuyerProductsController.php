<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\ApiController;

class BuyerProductsController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Buyer $buyer)
    {
        $products = $buyer->transactions()->with('product')
            ->get()
            ->pluck('Product');

        return $this->showAllSinCollection($products);
    }
}

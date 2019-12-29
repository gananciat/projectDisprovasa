<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductPricecontroller extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     */
    public function index(Product $product)
    {
        $prices = $product->prices;
        return $this->showAll($prices);
    }
}

<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\ApiController;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductPurchaseController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     */
    public function index(Product $product)
    {
        $purchases = $product->purchase_detail()
                             ->with('purchase.provider')->get()->sortByDesc('purchase.date')->values();

        return $this->showAll($purchases);
    }
}

<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Product;

class ProductCategoriesController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.credentials')->only(['index']);
        $this->middleware('auth:api')->except(['index']);
    }

    public function index(Product $product)
    {
        $categories = $product->categories;

        return $this->showAll($categories);
    }
}

<?php

namespace App\Http\Controllers\Category;

use App\Category;
use App\Http\Controllers\ApiController;

class CategoryBuyersController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Category $category)
    {
        $buyers = $category->products()
            ->whereHas('transactions')
            ->with('transactions.buyer')
            ->get()
            ->pluck('transactions')
            ->collapse()
            ->pluck('buyer')
            ->unique('id')
            ->values();

        return $this->showAllSinCollection($buyers);
    }
}

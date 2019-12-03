<?php

namespace App\Http\Controllers\Category;

use App\Category;
use App\Http\Controllers\ApiController;

class CategorySellersController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Category $category)
    {
        $sellers = $category->products()
            ->with('seller')
            ->get()
            ->pluck('seller')
            ->unique('id')
            ->values();

        return $this->showAllSinCollection($sellers);
    }
}

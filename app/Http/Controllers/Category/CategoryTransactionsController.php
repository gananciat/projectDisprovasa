<?php

namespace App\Http\Controllers\Category;

use App\Category;
use App\Http\Controllers\ApiController;

class CategoryTransactionsController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Category $category)
    {
        $transactions = $category->products()
            ->whereHas('transactions')
            ->with('transacitions')
            ->get()
            ->pluck('transacitions')
            ->collapse();

        return $this->showAll($transactions);
    }
}

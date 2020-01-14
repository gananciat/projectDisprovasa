<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Quantify;
use Carbon\Carbon;
use Illuminate\Http\Request;

class QuantifyController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $year = Carbon::now()->year;
        $quantifies = Quantify::with('product')->where('subtraction','>',0)->where('year',$year)->get();
        return $this->showAll($quantifies);
    }
}

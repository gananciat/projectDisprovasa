<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;

class SchoolBalanceController extends ApiController
{
    public function index(School $school)
    {
        $balances = $school->balances;
        return $this->showAll($balances);
    }
}

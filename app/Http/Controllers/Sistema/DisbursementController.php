<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Disbursement;
use Illuminate\Http\Request;

class DisbursementController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $disbursements = Disbursement::all();
        return $this->showAll($disbursements);
    }
}

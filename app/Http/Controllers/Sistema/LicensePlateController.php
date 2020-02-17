<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\LicensePlate;
use Illuminate\Http\Request;

class LicensePlateController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */    
    public function index()
    {
        return $this->showAll(LicensePlate::select('id','name','type')->orderBy('name')->get());
    }
}

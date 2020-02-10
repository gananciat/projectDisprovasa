<?php

namespace App\Http\Controllers\Sistema;

use App\Models\TypeLicense;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class TypeLicenseController extends ApiController
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
        return $this->showAll(TypeLicense::select('id','name','type')->orderBy('name')->get());
    }
}

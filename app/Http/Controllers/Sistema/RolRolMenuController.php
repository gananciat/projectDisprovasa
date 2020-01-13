<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Rol;
use Illuminate\Http\Request;

class RolRolMenuController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Rol $rol)
    {
        $rols = $rol->menus;
        return $this->showAll($rols);
    }
}

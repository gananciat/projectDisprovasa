<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\Controller;
use App\Models\Departament;
use Illuminate\Http\Request;

class DepartamentController extends Controller
{
    public function index()
    {
        return view('layouts.app_o');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Departament $departament)
    {
        //
    }

    public function edit(Departament $departament)
    {
        //
    }

    public function update(Request $request, Departament $departament)
    {
        //
    }

    public function destroy(Departament $departament)
    {
        //
    }
}

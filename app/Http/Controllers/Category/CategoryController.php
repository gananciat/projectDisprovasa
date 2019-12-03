<?php

namespace App\Http\Controllers\Category;

use App\Category;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class CategoryController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.credentials')->only(['index', 'show']);
        $this->middleware('auth:api')->except(['index', 'show']);
    }

    public function index()
    {
        $categories = Category::all();

        return $this->showAll($categories);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'description' => 'required'
        ];

        $this->validate($request, $rules);

        $category = Category::create($request->all());

        return $this->showOne($category, 201);
    }

    public function show(Category $category)
    {
        return $this->showOne($category);
    }

    public function update(Request $request, Category $category)
    {
        $category->fill($request->only(
            [
                'name',
                'description'
            ]
        ));

        if($category->isClean())
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);

        $category->save();

        return $this->showOne($category, 201);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        
        return $this->showOne($category);
    }
}

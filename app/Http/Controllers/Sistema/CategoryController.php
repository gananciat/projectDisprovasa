<?php

namespace App\Http\Controllers\Sistema;

use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CategoryController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $categories = Category::all();
        return $this->showAll($categories);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:75|unique:categories,name',
            'description' => 'nullable|string|max:250'
        ];
        
        $this->validate($request, $rules);
        $data = $request->all();
        $category = Category::create($data);

        return $this->showOne($category,201);
    }

    public function show(Category $category)
    {
        return $this->showOne($category);
    }

    public function update(Request $request, Category $category)
    {
        $rules = [
            'name' => 'required|string|max:75|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:250'
        ];

        $this->validate($request, $rules);

        $category->name = $request->name;
        $category->description = $request->description;

         if (!$category->isDirty()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $category->save();
        return $this->showOne($category);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return $this->showOne($category);
    }
}

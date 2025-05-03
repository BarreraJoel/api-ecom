<?php

namespace App\Services;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryService
{
    public function __construct() {}

    public function getAll(bool $resourceMode = false)
    {
        $categories = Category::all();
        if (!$resourceMode) {
            return $categories;
        }

        $categoriesWithResource = [];
        foreach ($categories as $category) {
            array_push($categoriesWithResource, new CategoryResource($category));
        }

        return $categoriesWithResource;
    }

    public function get(int $id)
    {
        $category = Category::find($id)->first();
        return isset($category) ? new CategoryResource($category) : null;
    }

    public function add(Request $request)
    {
        $category = Category::create($request->all());
        return isset($category) ? new CategoryResource($category) : null;
    }

    public function delete(Category $category)
    {
        return $category->delete();
    }

    public function update(Request $request, Category $category)
    {
        $category->fill(($request->except('_method')));
        return $category->update() ? true : false;
    }
}

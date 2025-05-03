<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponse;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    public function __construct(private CategoryService $categoryService) {}

    public function index()
    {
        return ApiResponse::response(
            true,
            null,
            [
                'categories' => $this->categoryService->getAll(true)
            ]
        );
    }

    public function store(Request $request)
    {
        $newCategory = $this->categoryService->add($request);
        if (!$newCategory) {
            throw new Exception('Hubo un error al agregar la categoría', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return ApiResponse::response(
            true,
            'Categoría agregada',
            ['category' => $newCategory],
            Response::HTTP_CREATED
        );
    }

    public function show(Category $category)
    {
        return ApiResponse::response(
            true,
            null,
            ['category' => $category]
        );
    }

    public function update(Request $request, Category $category)
    {
        if (!$this->categoryService->update($request, $category)) {
            throw new Exception('Hubo un error al actualizar la categoría', Response::HTTP_NOT_MODIFIED);
        }

        return ApiResponse::response(
            true,
            'Categoría actualizada',
            ['category' => new CategoryResource($category)]
        );
    }

    public function destroy(Category $category)
    {
        if (!$this->categoryService->delete($category)) {
            throw new Exception('No se pudo eliminar la categoría', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return ApiResponse::response(
            true,
            null,
            null,
            Response::HTTP_NO_CONTENT
        );
    }
}

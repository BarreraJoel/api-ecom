<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Decimal;
use Ramsey\Uuid\Type\Integer;

class FilterService
{
    public function __construct() {}

    public static function filter(Request $request)
    {
        $query = Product::query();

        if ($request->name) {
            $query = FilterService::filterByName($request->name);
        }
        if ($request->stock) {
            $query = FilterService::filterByStock($request->stock);
        }
        if ($request->price) {
            $query = FilterService::filterByPrice($request->price);
        }

        return $query->get();
    }

    public static function filterByName(string $name) {
        return Product::query()->where('name', $name);
    }

    public static function filterByPrice($price) {
        return Product::query()->where('price', $price);
    }

    public static function filterByStock($stock) {
        return Product::query()->where('stock', $stock);
    }
}

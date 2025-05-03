<?php

namespace App\Services;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Decimal;
use Ramsey\Uuid\Type\Integer;

class FilterService
{
    public function __construct() {}

    public static function filterProduct(Request $request)
    {
        $query = Product::query();

        if ($request->name) {
            $query = Product::query()->where('name', $request->name);
        }
        if ($request->stock) {
            $query = Product::query()->where('stock', $request->stock);
        }
        if ($request->price) {
            $query = Product::query()->where('price', $request->price);
        }
        if ($request->category_id) {
            $query = Product::query()->where('category_id', $request->price);
        }

        return $query->get();
    }

    public static function filterUser(Request $request)
    {
        $query = User::query();

        if ($request->name) {
            $query = User::query()->where('name', $request->name);
        }
        if ($request->lastname) {
            $query = User::query()->where('lastname', $request->lastname);
        }
        if ($request->role_id) {
            $query = User::query()->where('role_id', $request->role_id);
        }
        if ($request->email) {
            $query = User::query()->where('email', operator: $request->email);
        }


        return $query->get();
    }

}

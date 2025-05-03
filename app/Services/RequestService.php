<?php

namespace App\Services;

class RequestService
{
    public static function validate($request, array $keys)
    {
        foreach ($keys as $key) {
            if ($request->exists($key)) {
                return true;
            }
        }

        return false;
    }

    public static function lessThanOrEqual($request, string $key, int $size) {
        return $request[$key] <= $size;
    }

    public static function greaterThanOrEqual($request, string $key, int $size) {
        return $request[$key] >= $size;
    }

}

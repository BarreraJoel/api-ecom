<?php

namespace App\Services;
use Illuminate\Support\Facades\Validator;

class ValidationService
{

    public static function validate($request)
    {
        $validator = Validator::make($request->all(), $request->rules());

        if ($validator->fails()) {
            return false;
        }
        return true;
    }
}

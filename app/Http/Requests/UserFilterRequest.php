<?php

namespace App\Http\Requests;

use App\Classes\FormRequestValidationHandler;
use Illuminate\Foundation\Http\FormRequest;

class UserFilterRequest extends FormRequestValidationHandler
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'regex:/^[\pL\s\-]+$/u|min:1',
            'lastname' => 'regex:/^[\pL\s\-]+$/u|min:1',
            'email' => 'email|exists:users,email',
            'role_id' => 'integer|exists:roles,id',
        ];
    }
}

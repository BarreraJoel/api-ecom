<?php

namespace App\Http\Requests;

use App\Classes\FormRequestValidationHandler;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequestValidationHandler
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
            '_method' => 'required',
            'name' => 'min:3',
            'lastname' => 'min:3',
            'email' => 'email|unique:users,email',
            'password' => 'min:8|confirmed',
            'image_url' => 'image',
            'role_id' => 'exists:roles,id'
        ];
    }
}

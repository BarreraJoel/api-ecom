<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'name' => 'regex:/^[\pL\s\-]+$/u|min:2',
            'description' => 'regex:/^[\pL\s\.\-]+$/u|min:1',
            'price' => 'numeric|decimal:0,2',
            'stock' => 'integer|min:1',
            'image' => 'image'
        ];
    }
}

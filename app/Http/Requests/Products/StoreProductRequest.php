<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'name' => 'required|alpha|min:2',
            'description' => 'required|min:1',
            'image' => 'image',
            'price' => 'required|numeric|decimal:0,2',
            'stock' => 'required|integer|min:1',
            'category_id' => 'required|exists:categories,id'
        ];
    }
}

<?php

namespace App\Http\Requests\Product;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'         => 'required|string|min:3|max:25',
            'image'        => 'required|image:jpg,jpeg,png|max:10240',
//            автодобавление картинок с автоотображением (Пример:слайдер)
            'description'  => 'required|string|min:3',
            'content'      => 'required|string|min:10',
            'article'      => 'required|string|min:3|max:25',
            //            Код товара
            'price'        => 'required|bigInteger',
            'quantity'     => 'required|bigInteger',
        ];
    }
}

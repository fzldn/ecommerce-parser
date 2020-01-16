<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'page' => ['filled', 'integer', 'min:1'],
            'limit' => ['filled', 'integer', 'min:0'],
            'sort_by' => ['filled', Rule::in([
                'id',
                'title',
                'subtitle',
                'image',
                'thumbnail',
                'url',
                'upc',
                'gtin14',
                'price',
                'created_at'
            ])],
            'order' => ['filled', Rule::in(['asc', 'desc'])],
            'search' => ['string']
        ];
    }
}

<?php

namespace App\Presentation\API\Product\Requests;
use Illuminate\Foundation\Http\FormRequest;


class IndexProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'categoryIds' => 'nullable|array',
            'categoryIds.*' => 'integer|exists:categories,id',
            'minPrice' => 'nullable|numeric|min:0',
            'maxPrice' => 'nullable|numeric|min:0',
            'search' => 'nullable|string|max:255',
        ];
    }


    public function getCriteria(): array
    {
        return array_filter($this->validated());
    }
}
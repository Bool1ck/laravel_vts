<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCityRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'city_type_id' => 'required|int|exists:city_types,id',
            'name' => [
                'required',
                'string',
                // Валидация уникальности с учетом типа города
                Rule::unique('cities')->where(function ($query) {
                    return $query->where('city_type_id', $this->city_type_id);
                }),
            ],
        ];
    }
}

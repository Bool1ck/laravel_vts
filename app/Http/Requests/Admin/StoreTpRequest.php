<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTpRequest extends FormRequest
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
            'city_id'    => 'required|integer|exists:cities,id',
            'tp_type_id' => 'required|integer|exists:tp_types,id',
            'name'       => [
                'required',
                'string',
                'max:255',
                // ИСПРАВЛЕНО: добавляем правило уникальности в рамках этого же города и типа ТП
                Rule::unique('tps')->where(function ($query) {
                    return $query->where('city_id', $this->city_id)
                        ->where('tp_type_id', $this->tp_type_id);
                }),
            ],
        ];
    }
}

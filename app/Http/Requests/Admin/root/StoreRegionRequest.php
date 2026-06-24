<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\root;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRegionRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:regions,name',
            ],
        ];
    }

    /**
     * Кастомні повідомлення про помилки валідації українською мовою.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Назва регіону (РЕМ) є обов\'язковою для заповнення.',
            'name.string' => 'Назва регіону повинна бути текстовим рядком.',
            'name.max' => 'Назва регіону не повинна перевищувати 255 символів.',
            'name.unique' => 'Регіон (РЕМ) з такою назвою вже існує в системі.',
        ];
    }
}

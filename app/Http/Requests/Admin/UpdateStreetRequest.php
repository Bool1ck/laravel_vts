<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStreetRequest extends FormRequest
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
            'city_id' => 'required|exists:cities,id',
            'street_type_id' => 'required|exists:street_types,id',
            'name' => [
                'required', 'string',
                Rule::unique('streets')->where(fn ($query) => $query->where('street_type_id',
                    $this->street_type_id)->where('city_id', $this->city_id)),
            ],
        ];
    }
}

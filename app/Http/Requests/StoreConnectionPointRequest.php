<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreConnectionPointRequest extends FormRequest
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
            'region_id' => 'required|exists:regions,id',
            'technical_conditions' => 'required|string',
            'technical_conditions_date' => 'required|date_format:Y-m-d',
            'customer' => 'required|string',
            'power' => 'required|numeric',
            'customer_type_id' => 'required|exists:customer_types,id',
            'city_id' => 'required|exists:cities,id',
            'street_id' => 'required|exists:streets,id',
            'build_number' => 'required|string',
            'powerLineType' => 'required|string',
            'tp_id' => 'required|exists:tps,id',
            'power_line' => 'required|string',
            'pole' => 'required|numeric',
            'note' => 'string|nullable',
            'workTypes' => 'array|nullable',
        ];
    }
}

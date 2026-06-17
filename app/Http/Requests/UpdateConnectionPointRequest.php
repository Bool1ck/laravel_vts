<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateConnectionPointRequest extends FormRequest
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
            'technical_conditions' => 'required|string',
            'technical_conditions_date' => 'required|date_format:Y-m-d',
            'customer' => 'required|string',
            'customer_type_id' => 'required|exists:customer_types,id',
            'point_place' => 'required|string',
            'power_point' => 'required|string',
            'power' => 'required|numeric',
            'contract_date' => 'nullable|date_format:Y-m-d',
            'payment_date' => 'nullable|date_format:Y-m-d',
            'performance_date' => 'nullable|date_format:Y-m-d',
            'materials_order_date' => 'nullable|date_format:Y-m-d',
            'materials_receipt_date' => 'nullable|date_format:Y-m-d',
            'note' => 'string|nullable',
            'workTypes' => 'array|nullable',
        ];
    }
}

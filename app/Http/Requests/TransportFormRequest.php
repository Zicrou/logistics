<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransportFormRequest extends FormRequest
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
            'shipment_id' => ['required', 'uuid', 'exists:shipments,id'],
            'mode' => ['required', 'string', 'max:20'], 
            'status' => ['required', 'string', 'max:30'],
            'departure_date' => ['required', 'date'],
            'estimated_arrival' => ['required', 'date'],
            'actual_arrival' => ['required', 'date']
        ];
    }
}

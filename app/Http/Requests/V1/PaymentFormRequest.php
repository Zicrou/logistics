<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class PaymentFormRequest extends FormRequest
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
            'amount' => ['required','numeric','min:0.01','regex:/^\d+(\.\d{1,2})?$/'],
            'status' => ['required', 'string', 'max:20'],
            'method' => ['required', 'string', 'max:30'],
            'currency' => ['required','string','size:3','uppercase','regex:/^[A-Z]{3}$/',],
            'paid_at' => ['nullable', 'date']
        ];
    }
}

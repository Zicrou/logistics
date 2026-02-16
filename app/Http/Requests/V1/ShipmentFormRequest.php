<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class ShipmentFormRequest extends FormRequest
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
            'reference' => ['required','string','max:100'],
            'container_no' => ['required','string','max:50'],
            'cargo_type' => ['required','string','max:100'],
            'origin_port' => ['required','string','max:100'],
            'destination' => ['required','string','max:100'],
            'status' => ['required','string','in:pending,in_transit,delivered,cancelled'],
            'weight' => ['required','numeric','min:0'],
        ];
    }
}

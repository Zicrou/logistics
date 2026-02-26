<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DriverFormRequest extends FormRequest
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
            'full_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30','unique:drivers,phone'],
            'license_number' => ['required', 'string', 'max:50','unique:drivers,license_number'],
            'vehicle_id' => ['required', 'uuid', 'unique:drivers,id', 'exists:vehicles,id'],
            'last_seen' => ['nullable', 'date']
        ];
    }
}

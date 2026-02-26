<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DriverUpdateFormRequest extends FormRequest
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
        // $driver = $this->route('driver');
        return [
            'full_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30', Rule::unique('drivers', 'phone')->ignore($this->route('driver'), 'id')],
            'license_number' => ['required', 'string', 'max:50',Rule::unique('drivers', 'license_number')->ignore($this->route('driver'), 'id')],
            'vehicle_id' => ['required', 'uuid', 'unique:drivers,id', 'exists:vehicles,id'],
            'last_seen' => ['nullable', 'date']
        ];
    }
}

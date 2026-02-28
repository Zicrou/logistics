<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class LocationPointFormRequest extends FormRequest
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
            'shipment_id' => ['exists:shipments,id', 'uuid', 'required'],
            'longitude' => ['numeric', 'between:-180,180','regex:/^-?\d{1,2}(\.\d{1,8})?$/'],
            'latitude' => ['numeric', 'between:-90,90','regex:/^-?\d{1,2}(\.\d{1,8})?$/'],
            'speed' => ['nullable', 'numeric', 'min:0']
        ];
    }
}

<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class CheckpPointFormRequest extends FormRequest
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
            'type' =>  ['required', 'string', 'max:30'],
            'location' =>  ['required', 'string', 'max:100'],
            'status' =>  ['required', 'string', 'max:30'],
            'passed_at' =>  ['nullable', 'date']
        ];
    }
}

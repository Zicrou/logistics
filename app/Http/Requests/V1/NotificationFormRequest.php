<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class NotificationFormRequest extends FormRequest
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
            'user_id' => ['required', 'uuid', 'exists:users,id'],
            'title' => ['required', 'string', 'max:100'],
            'message' => ['string', 'min:1', 'max:1000'],
            'read' => ['boolean']
        ];
    }
}

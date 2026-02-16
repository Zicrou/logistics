<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class RegisterFormRequest extends FormRequest
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
            'name' => ['string','max:100'],
            'email' => ['email','unique:users', 'max:100'],
            'phone' => ['string','max:30','unique:users'],
            'password' => ['required','string','min:6'],
            'role' => ['required','string','in:user,admin,driver','max:20'],
            'active' => ['required','boolean'],
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Ім\'я обов\'язкове',
            'name.string' => 'Ім\'я має бути рядком',
            'name.max' => 'Максимальна довжина ім\'я 255 символів',
            'email.required' => 'Email обов\'язковий',
            'email.email' => 'Email має містити @',
            'email.string' => 'Email має бути рядком',
            'password.required' => 'Пароль обов\'язковий',
            'password.string' => 'Пароль має бути рядком',
            'password.min' => 'Мінімальна довжина пароля 8 символів'
        ];
    }
}

<?php declare(strict_types=1);

namespace App\Http\Requests;

use App\DTO\LoginDto;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email' => 'required|email|string|email',
            'password' => 'required|string',
            'remember_me' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email обов\'язковий',
            'email.email' => 'Email має містити @',
            'email.string' => 'Email має бути рядком',
            'password.required' => 'Пароль обов\'язковий',
            'password.string' => 'Пароль має бути рядком',
        ];
    }

    public function toDTO(): LoginDto
    {
        return LoginDto::fromArray($this->validated());
    }
}

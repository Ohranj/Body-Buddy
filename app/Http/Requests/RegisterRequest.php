<?php

namespace App\Http\Requests;

use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

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
            'forename' => ['required', 'max:50'],
            'surname' => ['required', 'max:50'],
            'email' => ['required', 'email', 'max:200', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8', 'max:100', function (string $attribute, mixed $value, Closure $fail) {
                $hasNumber = preg_match('/[0-9]/', $value);
                if (!$hasNumber) {
                    $fail('Passwords must contain at least 1 digit');
                }
                $hasUppercase = preg_match('/[A-Z]/', $value);
                if (!$hasUppercase) {
                    $fail('Passwords must contain at least 1 uppercase letter');
                }
            }],
            'password_confirmation' => ['required']
        ];
    }

    /**
     * 
     */
    protected function passedValidation(): void
    {
        $this->merge(['password_hashed' => Hash::make($this->password)]);
    }
}

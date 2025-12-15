<?php

namespace App\Http\Requests\CalorieTargets;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class CreateCalorieTargetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * 
     */
    public function prepareForValidation()
    {
        $this->merge([
            'formattedDate' => Carbon::parse($this->date)->startOfDay()
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'target' => ['required', 'numeric', 'min:1', 'max:10000'],
            'formattedDate' => ['required', 'date']
        ];
    }

    /**
     * 
     */
    public function messages(): array
    {
        return [
            'target.min' => 'Calorie targets must exceed 0',
            'amount.max' => 'Calorie targets must not exceed 10000',
        ];
    }
}

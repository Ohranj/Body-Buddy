<?php

namespace App\Http\Requests\Calories;

use App\Models\Calories;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class CreateCaloriesRequest extends FormRequest
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
            'amount' => $this->range,
            'meal' => strtoupper($this->meal['value']),
            'date' => Carbon::parse($this->time)->shiftTimezone('Europe/London')->setTimezone('UTC'),
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
            'amount' => ['required', 'numeric', 'max:3000'],
            'meal' => ['required', 'string', function ($attribute, $value, $fail) {
                $meals = [];
                foreach (Calories::MEALS as $meal) {
                    $meals[] = $meal['name'];
                }
                if (!in_array($value, $meals)) {
                    $fail('Invalid meal reference provided');
                }
            }],
            'date' => ['required', 'date'],
        ];
    }

    /**
     * 
     */
    public function messages(): array
    {
        return [
            'amount.max' => 'Calories to append must not exceed 3000',
        ];
    }
}

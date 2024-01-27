<?php

namespace App\Http\Requests;

use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class JobRequest extends FormRequest
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
            'request_body' => [
                'required',
                $this->getRequestBodyCustomRule()
            ]
        ];
    }

    private function getRequestBodyCustomRule(): Closure
    {
        return function (string $attribute, mixed $value, Closure $fail) {
            if (!is_array($value)) {
                $fail("The $attribute must be an array.");
                return;
            }

            foreach ($value as $requestBody) {
                if(
                    !is_array($requestBody) ||
                    !isset($requestBody['url']) ||
                    !filter_var($requestBody['url'], FILTER_VALIDATE_URL)
                ) {
                    $fail("Invalid valid urls.");
                    return;
                }

                if (
                    !is_array($requestBody['selectors']) ||
                    empty($requestBody['selectors'])
                ) {
                    $fail("Invalid structure for selectors.");
                    return;
                }

                foreach ($requestBody['selectors'] as $key => $selector) {
                    if (!is_string($key) || !is_string($selector)) {
                        $fail("Invalid selectors in $attribute.");
                        return;
                    }
                }
            }
        };
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new ValidationException($validator, response()->json([
            'errors' => $validator->errors()
        ], 422));
    }
}

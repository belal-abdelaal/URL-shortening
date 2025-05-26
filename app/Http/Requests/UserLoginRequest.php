<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    // public function authorize(): bool{return false;}

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "email" => "required|email|exists:users|max:50",
            "password" => "required|min:8|max:50"
        ];
    }
    protected function failedValidation(Validator $validator): void
    {
        // Build whatever JSON structure you like:
        $response = response([
            'errors'  => $validator->errors(),
        ], 400);

        throw new HttpResponseException($response);
    }
}

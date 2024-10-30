<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreWarehouseRequest extends FormRequest
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
            'warehouse_code' => ['required','unique:warehouses,warehouse_code','max:10'],
            'warehouse_name' => ['required','max:255','string'],
            'address' => ['nullable', 'string', 'max:500'],
            'phone_number' => ['nullable', 'regex:/^(?:\+94|0)?7[0-9]{8}$/'],
            'email' => ['nullable', 'email', 'max:255']
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errorMessages = $validator->errors();

        $fieldsWithErrors = $errorMessages->keys(); // Get the names of the fields with errors
        $message = count($fieldsWithErrors) > 1
            ? 'Some fields contain errors: ' . implode(', ', $fieldsWithErrors) . '. Please review the form.'
            : 'There is an error with the ' . $fieldsWithErrors[0] . ' field. Please correct it.';

        throw new HttpResponseException(response()->json([
            'message' => $message,
            'errors' => $errorMessages,
        ], 422));
    }
}

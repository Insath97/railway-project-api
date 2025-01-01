<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRoleStoreRequest extends FormRequest
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
            'office_id' => ['nullable', 'exists:offices,id'],
            'warehouse_id' => ['nullable', 'exists:warehouses,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:aadmins,email'],
            'password' => ['required', 'confirmed', 'min:8'],
            'password_confirmation' => ['required'],
            'role' => ['required'],
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

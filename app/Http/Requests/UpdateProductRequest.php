<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateProductRequest extends FormRequest
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
        $productId = $this->route('products');

        return [
            'main_category_id' => ['required', 'exists:main_categories,id'],
            'sub_category_id' => ['required', 'exists:sub_categories,id'],
            'unitType_id' => ['required', 'exists:unit_types,id'],
            'code' => ['required', 'string', 'unique:products,code,' . $productId, 'regex:/^[A-Z0-9]+$/', 'max:10'],
            'name' => ['required', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:50'],
            'size' => ['nullable', 'numeric', 'min:0'],
            'low_stock_threshold' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:500'],
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

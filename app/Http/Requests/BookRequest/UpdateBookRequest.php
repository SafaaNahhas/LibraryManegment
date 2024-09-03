<?php

namespace App\Http\Requests\BookRequest;

use Illuminate\Foundation\Http\FormRequest;

/**
 * UpdateBookRequest handles the validation of book update requests.
 */
class UpdateBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
    /**
     * Prepare the input data for validation.
     *
     * Capitalizes the first letter of each word in the 'title', 'author',
     * and 'classification' fields to ensure consistent formatting.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'title' => ucwords(strtolower($this->title)),
            'author' => ucwords(strtolower($this->author)),
            'classification' => ucwords(strtolower($this->classification)),

        ]);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'nullable|string|min:4|max:100',
            'author' => 'nullable|string|min:4|max:50',
            'classification' => 'nullable|string|min:4|max:50',
            'description' => 'nullable|string|min:50|max:200',
            'published_at' => 'nullable|date',
            'status' => 'nullable|in:available,borrowed',


        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        // Get all validation errors
        $errors = $validator->errors();

        // Construct a detailed error message
        $errorMessage = 'Validation failed for the following reasons:';
        foreach ($errors->all() as $error) {
            $errorMessage .= " - $error";
        }
    }

    /**
     * Perform any additional processing after validation passes.
     *
     * @return void
     */
    protected function passedValidation()
    {
        $this->merge([
            'title' => trim($this->input('title')),
            'author' => trim($this->input('author')),
            'date' =>date('Y-m-d',strtotime($this->input('published_at')) ),

        ]);
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'title' => 'اسم الكتاب',
            'author' => 'مؤلف الكتاب',
            'published_at' => 'تاريخ النشر',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'title.required' => 'اسم الكتاب مطلوب.',
            'author.required' => 'مؤلف الكتاب مطلوب ويجب أن يحتوي على 3 حروف على الأقل.',
            'published_at.required' => 'تاريخ النشر مطلوب.',
        ];
    }
}

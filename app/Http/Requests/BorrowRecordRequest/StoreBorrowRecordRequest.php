<?php
namespace App\Http\Requests\BorrowRecordRequest;

use Illuminate\Support\Facades\Log;
use App\Services\ApiResponseService;
use Illuminate\Foundation\Http\FormRequest;

class StoreBorrowRecordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'book_id' => 'required|exists:books,id',
            'borrowed_at' => 'required|date_format:Y-m-d',
            'due_date' => 'required|date_format:Y-m-d',
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

        'borrowed_at' => date('Y-m-d', strtotime($this->input('borrowed_at'))),
            'due_date' => date('Y-m-d', strtotime($this->input('due_date'))),

    ]);
        Log::info('Validation passed for BorrowRecordRequest');
    }
       /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'book_id' => 'المعرف الدال على الكتاب ',
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
            'book_id.required' => 'المعرف الدال على الكتاب مطلوب.',
            'book_id.exists' => 'المعرف الدال على الكتاب غير موجود.',
        ];
    }

}


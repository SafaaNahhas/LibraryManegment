<?php
namespace App\Http\Requests\BorrowRecordRequest;

use Illuminate\Support\Facades\Log;
use App\Services\ApiResponseService;
use Illuminate\Foundation\Http\FormRequest;

class ReturnBookRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'borrow_record_id' => 'required|exists:borrow_records,id',
            'due_date' => 'nullable|date|after_or_equal:borrowed_at',
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
        }}
     /**
     * Perform any additional processing after validation passes.
     *
     * @return void
     */
    protected function passedValidation()
    {
        $this->merge([

            'date' =>date('Y-m-d',strtotime($this->input('due_date')) ),

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
            'borrow_record_id' => 'المعرف الدال على سجل الاستعارة ',
            'due_date' => 'تاريخ الإعادة',
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
            'borrow_record_id.required' => 'المعرف الدال على سجل الاستعارة مطلوب.',
            'borrow_record_id.exists' => 'سجل الاستعارة غير موجود.',
            'due_date.required' => 'تاريخ الإعادة مطلوب.',
            'due_date.after_or_equal' => 'تاريخ الإعادة يجب أن يكون بعد أو يساوي تاريخ الاستعارة.',
        ];
    }
}

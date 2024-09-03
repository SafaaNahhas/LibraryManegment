<?php
namespace App\Http\Requests\RatingRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRatingRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'rating' => 'nullable|integer|min:1|max:5',
            'review' => 'nullable|string|max:255',
        ];
    }
}

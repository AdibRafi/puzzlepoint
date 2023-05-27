<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'type' => 'required',
            'answer.name' => 'required',
            'answer.right_answer' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'name' => 'Please Fill in the Question Name',
            'type' => 'Please Select Question Type',
            'answer.name' => 'Please Fill in the Answer Name',
            'answer.right_answer' => 'Please Choose the Right Answer for the Answer',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTopicRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'no_of_modules' => 'required|numeric|between:2,6',
            'max_time_expert' => 'required||numeric|between:1,100',
            'max_time_jigsaw' => 'required|numeric|between:1,100',
            'date_time' => 'required',
            'transition_time' => 'required|numeric|between:1,5'
        ];
    }
}

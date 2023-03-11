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

        //todo: Validation rule digits requires at least 1 parameters.
        return [
            'name' => 'required',
            'no_of_modules' => 'required|digits_between:2,6',
            'max_time_expert' => 'required|digits_between:0,100',
            'max_time_jigsaw' => 'required|digits_between:0,100',
            //todo: validation for date_time
//            'date_time' => 'required|date',


        ];
    }
}

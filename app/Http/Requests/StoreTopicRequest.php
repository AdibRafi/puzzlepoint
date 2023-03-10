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
            'topicName' => ['required'],
            'noOfModules' => ['required', 'digits'],
            'timeExpert' => ['required', 'digits'],
            'timeJigsaw' => ['required', 'digits'],
            'date' => ['required', 'date'],


        ];
    }
}

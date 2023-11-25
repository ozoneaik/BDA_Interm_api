<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class SettingFormRequest extends FormRequest
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
            'amount_of_longan' => 'required',
            'distance' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'amount_of_longan.required' => 'กรุณากรอกจำนวนผลลำไย',
            'distance.required' => 'กรุณากรอกระยะห่างช่อลำไย',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $baseFormRequest = new BaseFormRequest();
        return $baseFormRequest->responseFirstMessageError($validator);
    }
}

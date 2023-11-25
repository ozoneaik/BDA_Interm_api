<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFarmerFormRequest extends FormRequest
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
            'phone' => ['required','size:10'],
        ];
    }

    public function messages()
    {
        return [
            'phone.required' => 'กรุณากรอกเบอร์โทรศัพท์',
            'phone.size' => 'กรุณากรอกรูปแบบเบอร์โทรศัพท์ให้ถูกต้อง'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $baseFormRequest = new BaseFormRequest();
        return $baseFormRequest->responseFirstMessageError($validator);
    }
}

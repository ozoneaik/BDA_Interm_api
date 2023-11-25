<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseFormRequest;

class LoginFormRequest extends FormRequest
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
            'email' => 'required|email',
            'password' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'กรุณากรอกชื่อผู้ใช้',
            'email.email' => 'กรุณากรอกรูปแบบอีเมลให้ถูกต้อง',
            'password.required' => 'กรุณากรอกรหัสผ่าน'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $baseFormRequest = new BaseFormRequest();
        return $baseFormRequest->responseFirstMessageError($validator);
    }
}

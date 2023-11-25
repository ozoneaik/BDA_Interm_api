<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class WebApplicationRequest extends FormRequest
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
            'url' => 'required',
//            'picture' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'background_style' => 'required',
            'button_style' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'กรุณากรอกชื่อแอปพลิเคชัน',
            'url.required' => 'กรุณากรอกURLของแอปพลิเคชัน',
//            'picture.required' => 'กรุณาอัปโหลดไฟล์รูปภาพของแอปพลิเคชัน',
            'background_style.required' => 'กรุณาเลือกสีพื้นหลัง',
            'button_style.required' => 'กรุณาเลือกสีปุ่ม',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $baseFormRequest = new BaseFormRequest();
        return $baseFormRequest->responseFirstMessageError($validator);
    }
}

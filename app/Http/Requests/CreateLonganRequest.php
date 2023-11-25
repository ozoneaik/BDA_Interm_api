<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class CreateLonganRequest extends FormRequest
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
            'farmer_id' => 'required',
            'farm_id' => 'required',
            'name' => 'required|max:255',
            'location' => 'required|max:255',
            'type' => 'nullable|max:255',
            'specie' => 'required',
            'trimming_at' => 'nullable',
//            'pictures' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'กรุณากรอกชื่อต้นลำไย',
            'name.max' => 'ชื่อต้นลำไยมีความยาวได้ 255 อักษร',
            'location.required' => 'กรุณาเลือกตำแหน่งต้นลำไย',
            'specie.required' => 'กรุณาเลือกสายพันธุ์ต้นลำไย',
            'pictures.required' => 'กรุณาอัปโหลดรูปต้นลำไย'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $baseFormRequest = new BaseFormRequest();
        return $baseFormRequest->responseFirstMessageError($validator);
    }
}

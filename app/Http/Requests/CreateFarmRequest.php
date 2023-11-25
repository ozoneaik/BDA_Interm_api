<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class CreateFarmRequest extends FormRequest
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
            'location' => 'required',
            'polygons' => 'required',
            'amount_of_rai' => 'nullable',
            'amount_of_square_wa' => 'nullable',
            'amount_of_tree' => 'required',
            'file' => 'required|mimes:jpg,png',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'กรุณากรอกชื่อสวน',
            'location.required' => 'กรุณาเลือกตำแหน่งสวน',
            'polygons.required' => 'กรุณาปักหมุดตำแหน่งสวน',
            'amount_of_tree.required' => 'กรุณากรอกจำนวนต้น',
            'file.required' => 'กรุณาเลือกไฟล์',
            'file.mimes' => 'กรุณาเลือกไฟล์ประเภท jpg,bmp,png เท่านั้น'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $baseFormRequest = new BaseFormRequest();
        return $baseFormRequest->responseFirstMessageError($validator);
    }
}

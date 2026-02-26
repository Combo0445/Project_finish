<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ElderlyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'Name_Elderly' => 'required|string|max:255',
            'Gender' => 'required|string',
            'Birthday' => 'required|date',
            'Address' => 'required|string',
            'Phone_Elderly' => 'required|string',
            'Image_Elderly' => 'nullable|image|max:2048'
        ];
    }

    /**
     * Custom messages for validation errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'Name_Elderly.required' => 'กรุณากรอกชื่อ-นามสกุล',
            'Gender.required' => 'กรุณาเลือกเพศ',
            'Birthday.required' => 'กรุณากรอกวันเกิด',
            'Address.required' => 'กรุณากรอกที่อยู่',
            'Phone_Elderly.required' => 'กรุณากรอกเบอร์โทรศัพท์',
            'Image_Elderly.image' => 'ไฟล์ต้องเป็นรูปภาพเท่านั้น',
            'Image_Elderly.max' => 'รูปภาพต้องมีขนาดไม่เกิน 2MB',
        ];
    }
}

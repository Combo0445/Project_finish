<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $userId = Auth::id();

        return [
            'Name_User' => 'required|string|max:255',
            'Email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'Email')->ignore($userId, 'ID_User'),
            ],
            'Address' => 'nullable|string',
            'Phone' => 'nullable|string|max:20',
            'Image_User' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
            'Name_User.required' => 'กรุณากรอกชื่อ-นามสกุล',
            'Email.required' => 'กรุณากรอกอีเมล',
            'Email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
            'Email.unique' => 'อีเมลนี้ถูกใช้งานแล้ว',
            'Image_User.image' => 'ไฟล์ต้องเป็นรูปภาพเท่านั้น',
            'Image_User.max' => 'รูปภาพต้องมีขนาดไม่เกิน 2MB',
        ];
    }
}

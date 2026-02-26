<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
        $userId = $this->route('id');

        $rules = [
            'Name_User' => 'required|string|max:255',
            'Username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'Username')->ignore($userId, 'ID_User'),
            ],
            'Email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'Email')->ignore($userId, 'ID_User'),
            ],
            'Type_Personnel' => 'required|string',
            'line_token' => 'nullable|string|max:255',
            'Phone' => 'nullable|string|max:20',
            'Address' => 'nullable|string',
        ];

        if ($this->isMethod('POST')) {
            $rules['Password'] = 'required|string|min:6';
        } else {
            $rules['Password'] = 'nullable|string|min:6';
        }

        return $rules;
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
            'Username.required' => 'กรุณากรอกชื่อผู้ใช้',
            'Username.unique' => 'ชื่อผู้ใช้นี้ถูกใช้งานแล้ว',
            'Email.required' => 'กรุณากรอกอีเมล',
            'Email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
            'Email.unique' => 'อีเมลนี้ถูกใช้งานแล้ว',
            'Password.required' => 'กรุณากรอกรหัสผ่าน',
            'Password.min' => 'รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร',
            'Type_Personnel.required' => 'กรุณาเลือกประเภทบุคลากร',
        ];
    }
}

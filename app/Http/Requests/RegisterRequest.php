<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'phone_number' => [
                'required',
                'string',
                'regex:/^[0-9]\d{6,14}$/',
                'size:10',
                'unique:users,phone_number,NULL,id,phone_country_code,' . $this->phone_country_code,
            ],
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'agree_terms' => 'required|accepted'
        ];
    }

    public function messages(): array
    {
        return [
            'phone_number.required' => 'رقم الهاتف مطلوب',
            'phone_number.regex' => 'صيغة رقم الهاتف غير صحيحة',
            'phone_number.unique' => 'رقم الهاتف مستخدم مسبقاً',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة',
            'email.unique' => 'البريد الإلكتروني مستخدم مسبقاً',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'password.confirmed' => 'كلمة المرور غير متطابقة',
            'agree_terms.required' => 'يجب الموافقة على الشروط والأحكام',
            'agree_terms.accepted' => 'يجب الموافقة على الشروط والأحكام'
        ];
    }
}

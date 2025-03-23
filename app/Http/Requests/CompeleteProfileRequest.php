<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompeleteProfileRequest extends FormRequest
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
            'email' => 'required|email|exists:users,email',
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'gender' => 'nullable|in:male,female',
            'avatar' => 'nullable|image|max:2048',

            'label' => 'nullable|string|max:50',
            'country' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'street' => 'required|string|max:150',
            'postal_code' => 'nullable|numeric',
            'building_description' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_primary' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'البريد الإلكتروني مطلوب',
            'first_name.required' => 'الاسم الأول مطلوب',
            'last_name.required' => 'الاسم الأخير مطلوب',
            'gender'=>'النوع مطلوب',
            'avatar.image' => 'الصورة يجب أن تكون ملف صورة صالح',

            'label.string' => 'يجب أن يكون العنوان نصًا',
            'label.max' => 'يجب ألا يتجاوز العنوان 50 حرفًا',

            'country.required' => 'الدولة مطلوبة',
            'country.string' => 'يجب أن يكون اسم الدولة نصًا',
            'country.max' => 'يجب ألا يتجاوز اسم الدولة 100 حرف',

            'city.required' => 'المدينة مطلوبة',
            'city.string' => 'يجب أن يكون اسم المدينة نصًا',
            'city.max' => 'يجب ألا يتجاوز اسم المدينة 100 حرف',

            'district.required' => 'الحي مطلوب',
            'district.string' => 'يجب أن يكون اسم الحي نصًا',
            'district.max' => 'يجب ألا يتجاوز اسم الحي 100 حرف',

            'street.required' => 'الشارع مطلوب',
            'street.string' => 'يجب أن يكون اسم الشارع نصًا',
            'street.max' => 'يجب ألا يتجاوز اسم الشارع 150 حرف',

            'postal_code.numeric' => 'يجب أن يكون الرمز البريدي رقم',
            'postal_code.max' => 'يجب ألا يتجاوز الرمز البريدي 20 رقم',

            'building_description.string' => 'يجب أن يكون وصف المبنى نصًا',

            'latitude.numeric' => 'يجب أن يكون خط العرض رقمًا',
            'latitude.between' => 'يجب أن يكون خط العرض بين -90 و 90',

            'longitude.numeric' => 'يجب أن يكون خط الطول رقمًا',
            'longitude.between' => 'يجب أن يكون خط الطول بين -180 و 180',

            'is_primary.boolean' => 'يجب أن يكون الحقل أساسي صحيحًا أو خاطئًا',
        ];
    }
}

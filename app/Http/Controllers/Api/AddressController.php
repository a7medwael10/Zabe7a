<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $addresses = auth()->user()->addresses()->select('id','country','city','district','street')
            ->get();

        return $this->successResponse($addresses, 'تم جلب العناوين بنجاح',200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
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
        ], [
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
        ]);

        $address = auth()->user()->addresses()->create($validated);

        return $this->successResponse(null, 'تم إضافة العنوان بنجاح', 201);

    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
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
        ], [
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
        ]);

        $address = auth()->user()->addresses()->find($id);
        if (!$address) {
            return $this->errorResponse('العنوان غير موجود', 404);
        }

        $address->update($validated);

        return $this->successResponse(null, 'تم تحديث العنوان بنجاح', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $address = auth()->user()->addresses()->find($id);
        if (!$address) {
            return $this->errorResponse('العنوان غير موجود', 404);
        }

        $address->delete();

        return $this->successResponse(null, 'تم حذف العنوان بنجاح', 200);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\PackagingOption;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class PackagingOptionController extends Controller
{
    use ApiResponse;
    public function getPackagingOptions()
    {
        $options = PackagingOption::where('is_active', true)
            ->orderBy('type')
            ->orderBy('name')
            ->select('id','name','type','extra_price')
            ->get()
            ->groupBy('type');

        return $this->successResponse($options,'تم ارجاع خيارات التقطيع و التغليف بنجاخ',200);
    }

    public function chooseOptions(Request $request)
    {
        $validated = $request->validate([
            'item_id'            => 'required|exists:cart_items,id',
            'options'            => 'required|array',
            'options.cutting_id' => 'required|exists:packaging_options,id',
            'options.packaging_id' => 'required|exists:packaging_options,id',
            'options.liver_id'   => 'required|exists:packaging_options,id',
            'options.head_id'    => 'required|exists:packaging_options,id',
            'notes'              => 'nullable|string',
        ]);

        $cartItem = CartItem::findOrFail($validated['item_id']);

        // ✅ نجيب البيانات بتاعت الخيارات كلها مرة واحدة
        $optionIds = [
            $validated['options']['cutting_id'],
            $validated['options']['packaging_id'],
            $validated['options']['liver_id'],
            $validated['options']['head_id'],
        ];

        $options = PackagingOption::whereIn('id', $optionIds)->get();

        $optionsByType = [];
        foreach ($options as $option) {
            $optionsByType[$option->type] = $option->name;
        }

        $extraPrice = $options->sum('extra_price');

        $cartItem->packaging_options = $optionsByType;

        $cartItem->notes = $validated['notes'] ?? null;

        $cartItem->total += $extraPrice;

        $cartItem->save();

        return $this->successResponse($optionsByType, 'تم تحديد خيارات التقطيع والتغليف بنجاح', 201);
    }

}

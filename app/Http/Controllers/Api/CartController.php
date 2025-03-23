<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\DeliveryCompany;
use App\Models\Offer;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    use ApiResponse;

    public function showCart()
    {
        $user = auth()->user();

        $cart = Cart::where('user_id', $user->id)->with('cartItems.itemable')->first();

        if (!$cart) {
            return $this->errorResponse('السلة فارغة', 404);
        }

        $data = [
            'id'    => $cart->id,
            'subtotal' => $cart->subtotal,
            'shipping_cost' => $cart->shipping_cost,
            'total' => $cart->total,
            'items' => $cart->cartItems->map(function ($item) {
                return [
                    'id'       => $item->id,
                    'quantity' => $item->quantity,
                    'total'    => $item->total,
                    'name' => $item->itemable->title,
                    'image'=> $item->itemable->thumbnail_path ,
                    'price'=> $item->itemable->price,
                    'weight'=> $item->itemable->weight,
                    'options'  => $item->packaging_options ,
                    'notes'    => $item->notes ,
                ];
            }),
        ];

        return $this->successResponse($data);
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'type' => 'required|in:ad,offer',
            'id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = auth()->user();

        $cart = Cart::firstOrCreate(['user_id' => $user->id], [
            'subtotal' => 0,
            'discount' => 0,
            'shipping_cost' => 0,
            'total' => 0,
        ]);

        $itemableClass = $request->type === 'ad'
            ? Ad::class
            : Offer::class;

        $item = $itemableClass::findOrFail($request->id);

        $total = $item->price * $request->quantity;

        $cartItem =  $cart->cartItems()->create([
            'itemable_type' => $itemableClass,
            'itemable_id' => $item->id,
            'quantity' => $request->quantity,
            'total' => $total,
        ]);


        $cart->subtotal += $total;
        $cart->shipping_cost = DeliveryCompany::first()->base_price;
        $cart->total = $cart->subtotal + $cart->shipping_cost;

        $cart->save();

        return $this->successResponse([
            'Cart item id'=>$cartItem->id
        ],'تمت إضافة المنتج للسلة بنجاح' , 201);
    }

    public function addCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string',
        ],[
            'coupon_code.required' => 'الكوبون مطلوب',
            'coupon_code.string' => 'الكوبون يجب أن يكون نص',
        ]);

        $user = auth()->user();
        $cart = Cart::where('user_id', $user->id)->first();

        $coupon = Coupon::where('code', $request->coupon_code)
            ->where('is_active', true)
//            ->where('valid_from', '<=', now())
//            ->where('valid_to', '>=', now())
            ->first();

//        dd($coupon);
        if (!$coupon) {
            return $this->errorResponse('الكوبون غير صالح', 404);
        }

        if ($coupon->max_usage_per_user > 0 && $coupon->used_count >= $coupon->max_usage_per_user) {
            return $this->errorResponse('الكوبون تجاوز الحد الأقصى للاستخدام', 404);
        }

        if ($coupon->total_usage_limit > 0 && $coupon->used_count >= $coupon->total_usage_limit) {
            return $this->errorResponse('الكوبون تجاوز الحد الأقصى للاستخدام', 404);
        }

        $cart->coupon_id = $coupon->id;
        $cart->discount = $coupon->type === 'fixed'
            ? $coupon->value
            : $cart->subtotal * ($coupon->value / 100);

        $cart->total = $cart->subtotal + $cart->shipping_cost - $cart->discount;

        $cart->save();

        return $this->successResponse([
            'discount' => $cart->discount,
            'total' => $cart->total,
        ],'تمت إضافة الكوبون بنجاح');
    }

    public function removeFromCart(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|integer',
        ]);

        $user = auth()->user();
        $cart = Cart::where('user_id', $user->id)->first();

        $cartItem = $cart->cartItems()->find($request->cart_item_id);

        if (!$cartItem) {
            return $this->errorResponse('المنتج غير موجود في السلة', 404);
        }

        $cart->subtotal -= $cartItem->total;
        $cart->total = $cart->subtotal + $cart->shipping_cost - $cart->discount;

        $cart->save();

        $cartItem->delete();

        return $this->successResponse([
            'total' => $cart->total,
        ],'تمت إزالة المنتج من السلة بنجاح');
    }

    public function updateItemCount(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = auth()->user();

        $cart = Cart::where('user_id', $user->id)->first();

        $cartItem = $cart->cartItems()->find($request->cart_item_id);

        if (!$cartItem) {
            return $this->errorResponse('المنتج غير موجود في السلة', 404);
        }

        $cart->subtotal -= $cartItem->total;

        $cartItem->quantity = $request->quantity;
        $cartItem->total = $cartItem->itemable->price * $request->quantity;

        $cartItem->save();

        $cart->subtotal += $cartItem->total;

        $cart->total = $cart->subtotal + $cart->shipping_cost - $cart->discount;


        $cart->save();

        return $this->successResponse(null,'تم تعديل كمية المنتج من السلة بنجاح');

    }

}

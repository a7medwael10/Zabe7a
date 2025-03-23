<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderItemResource;
use App\Http\Resources\OrderResource;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ApiResponse;
    public function addOrder(Request $request)
    {
        $request->validate([
            'address_id' => 'required|integer|exists:addresses,id',
            'delivery_company_id' => 'required|integer|exists:delivery_companies,id',
        ]);

        $cart = auth()->user()->cart;

        if (!$cart) {
            return $this->errorResponse('السلة فارغة', 404);
        }

        $cartItems = $cart->cartItems;

        $order = auth()->user()->orders()->create([
           'order_number' => 'ord-'.uniqid(),
            'address_id' => $request->address_id,
            'delivery_company_id' => $request->delivery_company_id,
            'subtotal' => $cart->subtotal,
            'discount' => $cart->discount,
            'shipping_cost' => $cart->shipping_cost,
            'total' => $cart->total,
        ]);

        foreach ($cartItems as $cartItem) {
            $itemable = $cartItem->itemable;
            $order->orderItems()->create([
                'itemable_id'      => $cartItem->itemable_id,
                'itemable_type'    => $cartItem->itemable_type,
                'title'            => $itemable->title ,
                'unit_price'       => $itemable->price ,
                'quantity'         => $cartItem->quantity,
                'packaging_options'=> $cartItem->packaging_options ?? json_encode([]),
                'notes'            => $cartItem->notes,
                'total'            => $cartItem->total,
            ]);

            $cartItem->delete();

        }

        $cart->delete();

        return $this->successResponse(['order_id'=>$order->id], 'تم اضافة الطلب بنجاح',201);


    }

    public function getOrders()
    {
        $orders = auth()->user()->orders()->with('orderItems')->get();

        return $this->successResponse(OrderResource::collection($orders), 'تم جلب الطلبات بنجاح');
    }

    public function orderDetails(string $id)
    {
        $order = auth()->user()->orders()->find($id);

        if (!$order) {
            return $this->errorResponse('الطلب غير موجود', 404);
        }


        return $this->successResponse(
            [
                'order' => new OrderResource($order),
            ]
            , 'تم جلب تفاصيل الطلب بنجاح');
    }
}

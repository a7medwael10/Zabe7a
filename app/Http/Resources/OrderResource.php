<?php

namespace App\Http\Resources;

use App\Enums\OrderStatusEnum;
use App\Enums\PaymentStatusEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {


        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'subtotal' => $this->subtotal,
            'shipping_cost' => $this->shipping_cost,
            'total' => $this->total,
            'status' => OrderStatusEnum::from($this->status)->label(),
            'payment_status' =>  PaymentStatusEnum::from($this->payment_status)->label(),
            'paid_at' => $this->paid_at,
            'order_items' => OrderItemResource::collection($this->orderItems),
            'status_timeline' => $this->getOrderStatusTimeline(),
        ];
    }
}

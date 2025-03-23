<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Services\MoyasarService;
use App\Services\TabbyService;

class PaymentController extends Controller
{
    public function processPayment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        $order = Order::findOrFail($request->order_id);
        $paymentMethod = PaymentMethod::findOrFail($request->payment_method_id)->slug;
        switch ($paymentMethod) {
            case 'cash':
                return $this->handleCashPayment($order);

            case 'tabby':
                return $this->handleTabbyPayment($order);

            default:
                return $this->handleMoyasarPayment($order, $paymentMethod);
        }
    }

    private function handleCashPayment($order)
    {
        $order->update(['status' => 'pending']);
        return response()->json([
            'success' => true,
            'message' => 'سيتم تأكيد الطلب عند الاستلام'
        ]);
    }

    private function handleMoyasarPayment($order, $method)
    {
        $moyasar = new MoyasarService();
        $paymentData = [
            'amount' => $order->total * 100,
            'order_id' => $order->id,
            'method' => $method
        ];

        $result = $moyasar->initiatePayment($paymentData);

        return response()->json($result);
    }

    private function handleTabbyPayment($order)
    {
        $tabby = new TabbyService();
        $result = $tabby->createInstallmentSession([
            'amount' => $order->total,
            'order_id' => $order->id,
            'user' => auth()->user()
        ]);

        return response()->json($result);
    }

    public function handleMoyasarWebhook(Request $request)
    {


        $paymentId = $request->input('id');
        $orderId = $request->input('order_id');

        $order = Order::findOrFail($orderId);
        $order->update(['status' => 'paid']);

        return response()->json(['success' => true]);
    }

    public function handleTabbyWebhook(Request $request)
    {


        if ($request->status === 'closed') {
            $order = Order::findOrFail($request->order_id);
            $order->update(['status' => 'paid']);
        }

        return response()->json(['success' => true]);
    }
}

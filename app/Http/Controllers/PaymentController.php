<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function webhook(Request $request, PaymentService $paymentService)
    {
        try {
            Log::info('Payment webhook received', ['payload' => $request->all()]);
            $payment = $paymentService->createFromWebhook($request->all());
            return response()->json(['ok' => true, 'payment_id' => $payment->id]);
        } catch (\Exception $e) {
            Log::error('Payment webhook error', ['error' => $e->getMessage()]);
            return response()->json(['ok' => false, 'error' => $e->getMessage()], 500);
        }
    }
}



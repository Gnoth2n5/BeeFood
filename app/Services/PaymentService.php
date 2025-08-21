<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function createFromWebhook(array $payload): Payment
    {
        return DB::transaction(function () use ($payload) {
            $user = null;

            if(data_get($payload, 'transferAmount') != 2000){
                throw new \Exception('Số tiền không hợp lệ, vui lòng thanh toán 2.000đ');
            }

            $userId = explode('DH', $payload['description'])[1];

            $user = User::where('id', $userId)->first();

            if(!$user){
                throw new \Exception('Không tìm thấy tài khoản');
            }

            $payment = Payment::create([
                'user_id' => optional($user)->id,
                'gateway' => data_get($payload, 'gateway'),
                'transaction_date' => data_get($payload, 'transactionDate'),
                'account_number' => data_get($payload, 'accountNumber'),
                'code' => data_get($payload, 'code'),
                'content' => data_get($payload, 'content'),
                'transfer_type' => data_get($payload, 'transferType'),
                'transfer_amount' => (int) data_get($payload, 'transferAmount', 0),
                'accumulated' => data_get($payload, 'accumulated'),
                'sub_account' => data_get($payload, 'subAccount'),
                'reference_code' => data_get($payload, 'referenceCode'),
                'description' => data_get($payload, 'description'),
                'status' => 'completed',
                'raw_payload' => $payload,
            ]);

            if ($payment->user && $payload['transferAmount'] == 2000) {
                $payment->user->profile?->update(['isVipAccount' => true]);
            }

            return $payment;
        });
    }
}



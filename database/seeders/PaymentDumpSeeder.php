<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Payment;

class PaymentDumpSeeder extends Seeder
{
    /**
     * Run the seeder.
     */
    public function run(): void
    {
        // Clear existing data
        DB::table('payments')->truncate();

        // Insert data
        $data = [
            [
                'id' => 1,
                'user_id' => null,
                'gateway' => 'MBBank',
                'transaction_date' => '2025-08-18 23:36:00',
                'account_number' => 0383984836,
                'code' => null,
                'content' => 'DH7 FT25231105309258   Ma giao dich  Trace958278 Trace 958278',
                'transfer_type' => 'in',
                'transfer_amount' => 2000,
                'accumulated' => 0,
                'sub_account' => null,
                'reference_code' => 'FT25231713402115',
                'description' => 'BankAPINotify DH7 FT25231105309258   Ma giao dich  Trace958278 Trace 958278',
                'status' => 'completed',
                'raw_payload' => '{"id": 20749502, "code": null, "content": "DH7 FT25231105309258   Ma giao dich  Trace958278 Trace 958278", "gateway": "MBBank", "subAccount": null, "accumulated": 0, "description": "BankAPINotify DH7 FT25231105309258   Ma giao dich  Trace958278 Trace 958278", "transferType": "in", "accountNumber": "0383984836", "referenceCode": "FT25231713402115", "transferAmount": 2000, "transactionDate": "2025-08-18 23:36:00"}',
                'created_at' => '2025-08-18 16:46:47',
                'updated_at' => '2025-08-18 16:46:47',
            ],
            [
                'id' => 3,
                'user_id' => 7,
                'gateway' => 'MBBank',
                'transaction_date' => '2025-08-18 23:36:00',
                'account_number' => 0383984836,
                'code' => null,
                'content' => 'DH7 FT25231105309258   Ma giao dich  Trace958278 Trace 958278',
                'transfer_type' => 'in',
                'transfer_amount' => 2000,
                'accumulated' => 0,
                'sub_account' => null,
                'reference_code' => 'FT25231713402115',
                'description' => 'BankAPINotify DH7 FT25231105309258   Ma giao dich  Trace958278 Trace 958278',
                'status' => 'completed',
                'raw_payload' => '{"id": 20749502, "code": null, "content": "DH7 FT25231105309258   Ma giao dich  Trace958278 Trace 958278", "gateway": "MBBank", "subAccount": null, "accumulated": 0, "description": "BankAPINotify DH7 FT25231105309258   Ma giao dich  Trace958278 Trace 958278", "transferType": "in", "accountNumber": "0383984836", "referenceCode": "FT25231713402115", "transferAmount": 2000, "transactionDate": "2025-08-18 23:36:00"}',
                'created_at' => '2025-08-18 17:05:31',
                'updated_at' => '2025-08-18 17:05:31',
            ],
            [
                'id' => 4,
                'user_id' => 8,
                'gateway' => 'MBBank',
                'transaction_date' => '2025-08-20 11:53:00',
                'account_number' => 0383984836,
                'code' => null,
                'content' => 'DH8 FT25232893824864   Ma giao dich  Trace067790 Trace 067790',
                'transfer_type' => 'in',
                'transfer_amount' => 2000,
                'accumulated' => 0,
                'sub_account' => null,
                'reference_code' => 'FT25232517378155',
                'description' => 'BankAPINotify DH8 FT25232893824864   Ma giao dich  Trace067790 Trace 067790',
                'status' => 'completed',
                'raw_payload' => '{"id": 20877518, "code": null, "content": "DH8 FT25232893824864   Ma giao dich  Trace067790 Trace 067790", "gateway": "MBBank", "subAccount": null, "accumulated": 0, "description": "BankAPINotify DH8 FT25232893824864   Ma giao dich  Trace067790 Trace 067790", "transferType": "in", "accountNumber": "0383984836", "referenceCode": "FT25232517378155", "transferAmount": 2000, "transactionDate": "2025-08-20 11:53:00"}',
                'created_at' => '2025-08-20 04:54:57',
                'updated_at' => '2025-08-20 04:54:57',
            ],
            [
                'id' => 5,
                'user_id' => 9,
                'gateway' => 'MBBank',
                'transaction_date' => '2025-08-21 13:02:00',
                'account_number' => 0383984836,
                'code' => null,
                'content' => 'DH9 FT25233457026501   Ma giao dich  Trace719979 Trace 719979',
                'transfer_type' => 'in',
                'transfer_amount' => 2000,
                'accumulated' => 0,
                'sub_account' => null,
                'reference_code' => 'FT25233300409638',
                'description' => 'BankAPINotify DH9 FT25233457026501   Ma giao dich  Trace719979 Trace 719979',
                'status' => 'completed',
                'raw_payload' => '{"id": 20971655, "code": null, "content": "DH9 FT25233457026501   Ma giao dich  Trace719979 Trace 719979", "gateway": "MBBank", "subAccount": null, "accumulated": 0, "description": "BankAPINotify DH9 FT25233457026501   Ma giao dich  Trace719979 Trace 719979", "transferType": "in", "accountNumber": "0383984836", "referenceCode": "FT25233300409638", "transferAmount": 2000, "transactionDate": "2025-08-21 13:02:00"}',
                'created_at' => '2025-08-21 06:02:32',
                'updated_at' => '2025-08-21 06:02:32',
            ],
        ];

        DB::table('payments')->insert($data);
    }
}

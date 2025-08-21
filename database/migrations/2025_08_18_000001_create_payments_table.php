<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('gateway')->nullable();
            $table->dateTime('transaction_date')->nullable();
            $table->string('account_number')->nullable();
            $table->string('code')->nullable();
            $table->text('content')->nullable();
            $table->string('transfer_type')->nullable();
            $table->bigInteger('transfer_amount')->default(0);
            $table->bigInteger('accumulated')->nullable();
            $table->string('sub_account')->nullable();
            $table->string('reference_code')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('pending');
            $table->json('raw_payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};



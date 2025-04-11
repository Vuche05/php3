<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('payment_method'); // cod | vnpay
            $table->decimal('amount', 15, 2);
            $table->string('status')->default('pending'); // pending | completed | failed
            $table->string('transaction_id')->nullable(); // Ref code from VNPay
            $table->json('transaction_data')->nullable(); // Lưu thông tin từ VNPay
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
}

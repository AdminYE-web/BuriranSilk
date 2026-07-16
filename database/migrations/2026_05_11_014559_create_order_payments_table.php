<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_payments', function (Blueprint $table) {
            $table->id('order_payment_id');

            $table->unsignedBigInteger('order_id');

            $table->string('transaction_id')->nullable();
            $table->string('payment_method')->nullable();
            // credit_card, bank_transfer, promptpay, paypal, etc.

            $table->string('payment_status', 50)->default('pending');
            // pending, paid, failed, cancelled, refunded

            $table->decimal('amount', 12, 2)->default(0);
            $table->string('currency', 10)->default('JPY');

            $table->timestamp('paid_at')->nullable();

            $table->json('payment_response')->nullable();
            // เก็บ response จาก payment gateway ถ้ามี

            $table->timestamps();

            $table->foreign('order_id')
                ->references('order_id')
                ->on('orders')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_payments');
    }
};

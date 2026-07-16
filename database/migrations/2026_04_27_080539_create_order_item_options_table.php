<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_item_options', function (Blueprint $table) {
            $table->id('order_item_option_id');

            $table->unsignedBigInteger('order_item_id');

            $table->unsignedBigInteger('option_group_id')->nullable();
            $table->unsignedBigInteger('option_id')->nullable();

            $table->string('group_name_snapshot');
            $table->string('option_name_snapshot')->nullable();

            $table->decimal('additional_price', 10, 2)->default(0);

            $table->enum('price_type', ['per_item', 'per_order', 'text'])->default('per_item');

            $table->text('custom_value')->nullable();
            // ใช้กับข้อ 12 ตัวเลือกเพิ่มไว้กรอกขอตัวสินค้า

            $table->decimal('total_price', 10, 2)->default(0);

            $table->timestamps();

            $table->foreign('order_item_id')
                ->references('order_item_id')
                ->on('order_items')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};

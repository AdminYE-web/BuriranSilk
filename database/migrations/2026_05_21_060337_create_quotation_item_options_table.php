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
        // ตรวจสอบก่อนว่าถ้า "ยังไม่มี" ตารางนี้ ถึงจะทำการสร้าง
        if (! Schema::hasTable('quotation_item_options')) {
            Schema::create('quotation_item_options', function (Blueprint $table) {
                $table->id('quotation_item_option_id');

                $table->unsignedBigInteger('quotation_item_id');
                $table->unsignedBigInteger('option_group_id')->nullable();
                $table->unsignedBigInteger('option_id')->nullable();
                $table->unsignedBigInteger('variant_id')->nullable();

                $table->string('group_name')->nullable();
                $table->string('option_name')->nullable();
                $table->string('variant_name')->nullable();

                $table->decimal('additional_price', 12, 2)->default(0);
                $table->string('price_type', 30)->nullable(); // per_item, per_order

                $table->timestamps();

                $table->foreign('quotation_item_id')
                    ->references('quotation_item_id')
                    ->on('quotation_items')
                    ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_item_options');
    }
};

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
      Schema::create('product_option_price_rates', function (Blueprint $table) {
    $table->id('rate_id');

    // ถ้า project ใช้ชื่อ primary key เป็น option_id ให้ใช้ option_id
    $table->unsignedBigInteger('option_id');

    $table->integer('min_qty')->default(1);
    $table->decimal('additional_price', 10, 2)->default(0);
    $table->decimal('additional_price_with_tax', 10, 2)->default(0);

    $table->timestamps();

    $table->index(['option_id', 'min_qty']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_option_price_rates');
    }
};

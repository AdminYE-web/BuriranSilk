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
        Schema::create('products', function (Blueprint $table) {
            $table->id('product_id');

            $table->string('product_code')->nullable();
            // เช่น FLAT_10MM, FLAT_15MM

            $table->string('product_name');
            // เช่น ทอแบบเรียบ 10มม.

            $table->text('description')->nullable();

            $table->boolean('is_antivirus_included')->default(false);
            // ถ้าสินค้านี้รวมยาต้านไวรัสอยู่แล้ว

            $table->boolean('is_active')->default(true);

            $table->timestamps();
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

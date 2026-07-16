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
        Schema::create('product_price_tiers', function (Blueprint $table) {
            $table->id('tier_id');

            $table->unsignedBigInteger('product_id');

            $table->integer('min_qty');
            $table->integer('max_qty')->nullable();
            // nullable = ไม่จำกัด เช่น 201 ชิ้นขึ้นไป

            $table->decimal('unit_price', 10, 2);
            // ราคาต่อชิ้น

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->foreign('product_id')
                ->references('product_id')
                ->on('products')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_price_tiers');
    }
};

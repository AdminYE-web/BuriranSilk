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
        Schema::create('quotation_items', function (Blueprint $table) {
            $table->id('quotation_item_id');

            $table->unsignedBigInteger('quotation_id');
            $table->unsignedBigInteger('product_id');

            $table->string('product_name_snapshot');
            $table->string('product_code_snapshot')->nullable();

            $table->integer('quantity')->default(1);

            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('option_total', 12, 2)->default(0);
            $table->decimal('item_total', 12, 2)->default(0);

            $table->json('price_rule_snapshot')->nullable();

            $table->timestamps();

            $table->foreign('quotation_id')
                ->references('quotation_id')
                ->on('quotations')
                ->onDelete('cascade');

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
        Schema::dropIfExists('quotation_items');
    }
};

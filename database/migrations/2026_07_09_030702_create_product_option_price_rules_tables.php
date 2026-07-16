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
    Schema::create('product_option_price_rules', function (Blueprint $table) {
        $table->id('option_price_rule_id');
        $table->unsignedBigInteger('product_id');
        $table->string('rule_name');
        $table->boolean('is_active')->default(1);
        $table->timestamps();

        $table->foreign('product_id')
            ->references('product_id')
            ->on('products')
            ->cascadeOnDelete();
    });

    Schema::create('product_option_price_rule_options', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('option_price_rule_id');
        $table->unsignedBigInteger('option_id');

        $table->foreign('option_price_rule_id')
            ->references('option_price_rule_id')
            ->on('product_option_price_rules')
            ->cascadeOnDelete();

        $table->foreign('option_id')
            ->references('option_id')
            ->on('product_options')
            ->cascadeOnDelete();
    });

    Schema::create('product_option_price_rule_tiers', function (Blueprint $table) {
        $table->id('option_price_rule_tier_id');
        $table->unsignedBigInteger('option_price_rule_id');
        $table->integer('min_qty');
        $table->integer('max_qty')->nullable();
        $table->decimal('additional_price', 12, 2)->default(0);
        $table->decimal('additional_price_with_tax', 12, 2)->nullable();
        $table->boolean('is_active')->default(1);
        $table->timestamps();

        $table->foreign('option_price_rule_id')
            ->references('option_price_rule_id')
            ->on('product_option_price_rules')
            ->cascadeOnDelete();
    });
}

public function down(): void
{
    Schema::dropIfExists('product_option_price_rule_tiers');
    Schema::dropIfExists('product_option_price_rule_options');
    Schema::dropIfExists('product_option_price_rules');
}
};

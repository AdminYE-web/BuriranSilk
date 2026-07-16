<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_price_rule_tiers', function (Blueprint $table) {
            $table->id('tier_id');

            $table->unsignedBigInteger('rule_id');

            $table->integer('min_qty');
            $table->integer('max_qty')->nullable();
            $table->decimal('unit_price', 10, 2);

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('rule_id')
                ->references('rule_id')
                ->on('product_price_rules')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_price_rule_tiers');
    }
};

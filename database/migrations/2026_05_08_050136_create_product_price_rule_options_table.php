<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_price_rule_options', function (Blueprint $table) {
            $table->id('rule_option_id');

            $table->unsignedBigInteger('rule_id');
            $table->unsignedBigInteger('option_id');

            $table->timestamps();

            $table->foreign('rule_id')
                ->references('rule_id')
                ->on('product_price_rules')
                ->onDelete('cascade');

            $table->foreign('option_id')
                ->references('option_id')
                ->on('product_options')
                ->onDelete('cascade');

            $table->unique(['rule_id', 'option_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_price_rule_options');
    }
};

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
        Schema::table('product_option_price_rules', function (Blueprint $table) {
            $table->unsignedBigInteger('target_option_id')
                ->nullable()
                ->after('product_id');

            $table->foreign('target_option_id')
                ->references('option_id')
                ->on('product_options')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_option_price_rules', function (Blueprint $table) {
            $table->dropForeign(['target_option_id']);
            $table->dropColumn('target_option_id');
        });
    }
};

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
        Schema::table('product_option_variants', function (Blueprint $table) {
            $table->decimal('additional_price_with_tax', 10, 2)->nullable()->after('additional_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_option_variants', function (Blueprint $table) {
            $table->dropColumn('additional_price_with_tax');
        });
    }
};

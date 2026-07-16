<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_price_rule_tiers', function (Blueprint $table) {
            $table->decimal('unit_price_with_tax', 12, 2)
                ->nullable()
                ->after('unit_price');
        });
    }

    public function down(): void
    {
        Schema::table('product_price_rule_tiers', function (Blueprint $table) {
            $table->dropColumn('unit_price_with_tax');
        });
    }
};

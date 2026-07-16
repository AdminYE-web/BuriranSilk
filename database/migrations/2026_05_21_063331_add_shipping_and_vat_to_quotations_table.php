<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->decimal('shipping_fee', 12, 2)->default(0)->after('discount_amount');
            $table->decimal('vat_amount', 12, 2)->default(0)->after('shipping_fee');
        });
    }

    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn(['shipping_fee', 'vat_amount']);
        });
    }
};

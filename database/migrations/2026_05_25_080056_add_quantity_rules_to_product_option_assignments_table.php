<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_option_assignments', function (Blueprint $table) {
            $table->string('qty_rule_type', 30)->nullable()->after('is_active');
            $table->integer('min_qty')->nullable()->after('qty_rule_type');
            $table->integer('max_qty')->nullable()->after('min_qty');
            $table->integer('exact_qty')->nullable()->after('max_qty');
        });
    }

    public function down(): void
    {
        Schema::table('product_option_assignments', function (Blueprint $table) {
            $table->dropColumn([
                'qty_rule_type',
                'min_qty',
                'max_qty',
                'exact_qty',
            ]);
        });
    }
};

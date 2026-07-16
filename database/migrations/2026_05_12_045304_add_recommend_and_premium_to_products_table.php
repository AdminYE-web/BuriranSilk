<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'product_recomend')) {
                $table->tinyInteger('product_recomend')
                    ->default(0)
                    ->after('is_active');
            }

            if (! Schema::hasColumn('products', 'product_premium')) {
                $table->tinyInteger('product_premium')
                    ->default(0)
                    ->after('product_recomend');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'product_recomend')) {
                $table->dropColumn('product_recomend');
            }

            if (Schema::hasColumn('products', 'product_premium')) {
                $table->dropColumn('product_premium');
            }
        });
    }
};

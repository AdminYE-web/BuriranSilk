<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (! Schema::hasColumn('order_items', 'product_name')) {
                $table->string('product_name')->nullable()->after('product_id');
            }

            if (! Schema::hasColumn('order_items', 'product_image')) {
                $table->string('product_image')->nullable()->after('product_name');
            }

            if (! Schema::hasColumn('order_items', 'quantity')) {
                $table->integer('quantity')->default(1)->after('product_image');
            }

            if (! Schema::hasColumn('order_items', 'price_rule_id')) {
                $table->unsignedBigInteger('price_rule_id')->nullable()->after('quantity');
            }

            if (! Schema::hasColumn('order_items', 'price_rule_name')) {
                $table->string('price_rule_name')->nullable()->after('price_rule_id');
            }

            if (! Schema::hasColumn('order_items', 'unit_price')) {
                $table->decimal('unit_price', 10, 2)->default(0)->after('price_rule_name');
            }

            if (! Schema::hasColumn('order_items', 'product_total')) {
                $table->decimal('product_total', 10, 2)->default(0)->after('unit_price');
            }

            if (! Schema::hasColumn('order_items', 'option_total')) {
                $table->decimal('option_total', 10, 2)->default(0)->after('product_total');
            }

            if (! Schema::hasColumn('order_items', 'item_total')) {
                $table->decimal('item_total', 10, 2)->default(0)->after('option_total');
            }

            if (! Schema::hasColumn('order_items', 'options')) {
                $table->json('options')->nullable()->after('item_total');
            }

            if (! Schema::hasColumn('order_items', 'custom_colors')) {
                $table->json('custom_colors')->nullable()->after('options');
            }
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $columns = [
                'product_name',
                'product_image',
                'quantity',
                'price_rule_id',
                'price_rule_name',
                'unit_price',
                'product_total',
                'option_total',
                'item_total',
                'options',
                'custom_colors',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('order_items', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

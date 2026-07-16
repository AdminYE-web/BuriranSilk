<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'total_items')) {
                $table->integer('total_items')->default(0)->after('user_id');
            }

            if (! Schema::hasColumn('orders', 'total_quantity')) {
                $table->integer('total_quantity')->default(0)->after('total_items');
            }

            if (! Schema::hasColumn('orders', 'shipping_fee')) {
                $table->decimal('shipping_fee', 10, 2)->default(0)->after('subtotal');
            }

            if (! Schema::hasColumn('orders', 'tax_amount')) {
                $table->decimal('tax_amount', 10, 2)->default(0)->after('shipping_fee');
            }

            if (! Schema::hasColumn('orders', 'currency')) {
                $table->string('currency', 10)->default('JPY')->after('grand_total');
            }

            if (! Schema::hasColumn('orders', 'order_status')) {
                $table->string('order_status', 50)->default('pending')->after('currency');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'order_status')) {
                $table->dropColumn('order_status');
            }

            if (Schema::hasColumn('orders', 'currency')) {
                $table->dropColumn('currency');
            }

            if (Schema::hasColumn('orders', 'tax_amount')) {
                $table->dropColumn('tax_amount');
            }

            if (Schema::hasColumn('orders', 'shipping_fee')) {
                $table->dropColumn('shipping_fee');
            }

            if (Schema::hasColumn('orders', 'total_quantity')) {
                $table->dropColumn('total_quantity');
            }

            if (Schema::hasColumn('orders', 'total_items')) {
                $table->dropColumn('total_items');
            }
        });
    }
};

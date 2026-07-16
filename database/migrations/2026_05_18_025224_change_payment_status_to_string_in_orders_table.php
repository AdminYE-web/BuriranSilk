<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('orders', 'order_status')) {
            DB::statement("
                UPDATE orders
                SET order_status = 'pending'
                WHERE order_status IS NULL
                   OR order_status = ''
            ");

            DB::statement("
                ALTER TABLE orders
                MODIFY order_status VARCHAR(50) NOT NULL DEFAULT 'pending'
            ");
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('orders', 'order_status')) {
            DB::statement("
                ALTER TABLE orders
                MODIFY order_status VARCHAR(50) NOT NULL DEFAULT 'pending'
            ");
        }
    }
};

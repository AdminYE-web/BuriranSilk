<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Normalize old invalid statuses first
        |--------------------------------------------------------------------------
        | กัน error Data truncated ก่อนเปลี่ยน type
        |--------------------------------------------------------------------------
        */
        DB::statement("
            UPDATE orders
            SET status = 'pending'
            WHERE status IS NULL
               OR status = ''
        ");

        DB::statement("
            ALTER TABLE orders
            MODIFY status VARCHAR(50) NOT NULL DEFAULT 'pending'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE orders
            MODIFY status VARCHAR(50) NOT NULL DEFAULT 'pending'
        ");
    }
};

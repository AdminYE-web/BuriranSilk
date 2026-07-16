<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_options', function (Blueprint $table) {
            $table->integer('free_from_qty')
                ->nullable()
                ->after('additional_price')
                ->comment('If order quantity is greater than or equal to this value, additional price becomes free');
        });
    }

    public function down(): void
    {
        Schema::table('product_options', function (Blueprint $table) {
            $table->dropColumn('free_from_qty');
        });
    }
};

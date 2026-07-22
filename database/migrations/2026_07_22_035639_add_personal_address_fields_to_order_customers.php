<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_customers', function (Blueprint $table) {
            $table->string('personal_postcode')->nullable()->after('personal_email');
            $table->string('personal_province')->nullable()->after('personal_postcode');
            $table->string('personal_city')->nullable()->after('personal_province');
            $table->string('personal_area')->nullable()->after('personal_city');
        });
    }

    public function down(): void
    {
        Schema::table('order_customers', function (Blueprint $table) {
            $table->dropColumn([
                'personal_postcode',
                'personal_province',
                'personal_city',
                'personal_area',
            ]);
        });
    }
};

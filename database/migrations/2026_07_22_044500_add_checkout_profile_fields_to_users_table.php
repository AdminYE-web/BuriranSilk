<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('customer_type', 20)->nullable()->after('last_name');
            $table->string('last_name_kana')->nullable()->after('customer_type');
            $table->string('first_name_kana')->nullable()->after('last_name_kana');
            $table->string('company_name')->nullable()->after('first_name_kana');
            $table->string('company_name_kana')->nullable()->after('company_name');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'customer_type',
                'last_name_kana',
                'first_name_kana',
                'company_name',
                'company_name_kana',
            ]);
        });
    }
};

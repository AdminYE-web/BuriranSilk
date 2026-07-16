<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('option_dependencies', function (Blueprint $table) {
            $table->string('action_type')->default('show')->after('target_type');
        });
    }

    public function down(): void
    {
        Schema::table('option_dependencies', function (Blueprint $table) {
            $table->dropColumn('action_type');
        });
    }
};

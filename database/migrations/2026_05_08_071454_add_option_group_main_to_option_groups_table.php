<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('option_groups', function (Blueprint $table) {
            if (! Schema::hasColumn('option_groups', 'option_group_main')) {
                $table->tinyInteger('option_group_main')
                    ->default(0)
                    ->after('is_required');
            }
        });
    }

    public function down(): void
    {
        Schema::table('option_groups', function (Blueprint $table) {
            if (Schema::hasColumn('option_groups', 'option_group_main')) {
                $table->dropColumn('option_group_main');
            }
        });
    }
};

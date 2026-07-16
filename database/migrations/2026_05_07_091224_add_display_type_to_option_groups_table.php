<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('option_groups', function (Blueprint $table) {
            if (! Schema::hasColumn('option_groups', 'display_type')) {
                $table->string('display_type')
                    ->default('button')
                    ->after('group_name');
            }

            if (! Schema::hasColumn('option_groups', 'is_required')) {
                $table->boolean('is_required')
                    ->default(true)
                    ->after('display_type');
            }

            if (! Schema::hasColumn('option_groups', 'sort_order')) {
                $table->integer('sort_order')
                    ->default(0)
                    ->after('is_required');
            }
        });
    }

    public function down(): void
    {
        Schema::table('option_groups', function (Blueprint $table) {
            if (Schema::hasColumn('option_groups', 'sort_order')) {
                $table->dropColumn('sort_order');
            }

            if (Schema::hasColumn('option_groups', 'is_required')) {
                $table->dropColumn('is_required');
            }

            if (Schema::hasColumn('option_groups', 'display_type')) {
                $table->dropColumn('display_type');
            }
        });
    }
};

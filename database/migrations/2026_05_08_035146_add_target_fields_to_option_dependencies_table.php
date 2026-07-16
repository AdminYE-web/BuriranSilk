<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('option_dependencies', function (Blueprint $table) {
            $table->string('target_type', 20)
                ->default('option')
                ->after('parent_option_id');

            $table->unsignedBigInteger('target_group_id')
                ->nullable()
                ->after('target_type');

            $table->unsignedBigInteger('target_option_id')
                ->nullable()
                ->after('target_group_id');

            $table->boolean('is_active')
                ->default(true)
                ->after('child_option_id');

            $table->integer('sort_order')
                ->default(0)
                ->after('is_active');
        });

        // ย้ายข้อมูลเดิมจาก child_option_id ไป target_option_id
        DB::table('option_dependencies')
            ->whereNotNull('child_option_id')
            ->update([
                'target_type' => 'option',
                'target_option_id' => DB::raw('child_option_id'),
            ]);

        Schema::table('option_dependencies', function (Blueprint $table) {
            $table->foreign('target_group_id')
                ->references('option_group_id')
                ->on('option_groups')
                ->onDelete('cascade');

            $table->foreign('target_option_id')
                ->references('option_id')
                ->on('product_options')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('option_dependencies', function (Blueprint $table) {
            $table->dropForeign(['target_group_id']);
            $table->dropForeign(['target_option_id']);

            $table->dropColumn([
                'target_type',
                'target_group_id',
                'target_option_id',
                'is_active',
                'sort_order',
            ]);
        });
    }
};

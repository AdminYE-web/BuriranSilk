<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('identify_id')
                ->nullable()
                ->after('email');

            $table->unsignedTinyInteger('social_type')
                ->nullable()
                ->after('identify_id')
                ->comment('1 = Google, 2 = LINE');

        

            $table->unique(
                ['social_type', 'identify_id'],
                'users_social_identify_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_social_identify_unique');

            $table->dropColumn([
                'identify_id',
                'social_type',
            ]);
        });
    }
};
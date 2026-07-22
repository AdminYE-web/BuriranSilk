<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'identify_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('identify_id')
                    ->nullable()
                    ->after('email');
            });
        }

        if (! Schema::hasColumn('users', 'social_type')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedTinyInteger('social_type')
                    ->nullable()
                    ->after('identify_id')
                    ->comment('1 = Google, 2 = LINE');
            });
        }

        if (! Schema::hasIndex('users', 'users_social_identify_unique')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unique(
                    ['social_type', 'identify_id'],
                    'users_social_identify_unique'
                );
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasIndex('users', 'users_social_identify_unique')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropUnique('users_social_identify_unique');
            });
        }

        Schema::table('users', function (Blueprint $table) {
            $columns = collect(['identify_id', 'social_type'])
                ->filter(fn (string $column) => Schema::hasColumn('users', $column))
                ->all();

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};

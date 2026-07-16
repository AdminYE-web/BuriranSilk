<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'can_upload_artwork')) {
                $table->tinyInteger('can_upload_artwork')
                    ->default(0)
                    ->after('is_active');
            }

            if (! Schema::hasColumn('products', 'artwork_required')) {
                $table->tinyInteger('artwork_required')
                    ->default(0)
                    ->after('can_upload_artwork');
            }

            if (! Schema::hasColumn('products', 'allow_no_artwork')) {
                $table->tinyInteger('allow_no_artwork')
                    ->default(1)
                    ->after('artwork_required');
            }

            if (! Schema::hasColumn('products', 'allow_text_print')) {
                $table->tinyInteger('allow_text_print')
                    ->default(0)
                    ->after('allow_no_artwork');
            }

            if (! Schema::hasColumn('products', 'allow_font_select')) {
                $table->tinyInteger('allow_font_select')
                    ->default(0)
                    ->after('allow_text_print');
            }

            if (! Schema::hasColumn('products', 'allow_template_select')) {
                $table->tinyInteger('allow_template_select')
                    ->default(0)
                    ->after('allow_font_select');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'allow_template_select')) {
                $table->dropColumn('allow_template_select');
            }

            if (Schema::hasColumn('products', 'allow_font_select')) {
                $table->dropColumn('allow_font_select');
            }

            if (Schema::hasColumn('products', 'allow_text_print')) {
                $table->dropColumn('allow_text_print');
            }

            if (Schema::hasColumn('products', 'allow_no_artwork')) {
                $table->dropColumn('allow_no_artwork');
            }

            if (Schema::hasColumn('products', 'artwork_required')) {
                $table->dropColumn('artwork_required');
            }

            if (Schema::hasColumn('products', 'can_upload_artwork')) {
                $table->dropColumn('can_upload_artwork');
            }
        });
    }
};

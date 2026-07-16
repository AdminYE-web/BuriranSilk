<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_artworks', function (Blueprint $table) {
            if (! Schema::hasColumn('order_artworks', 'order_id')) {
                $table->unsignedBigInteger('order_id')->nullable()->after('id');
            }

            if (! Schema::hasColumn('order_artworks', 'order_item_id')) {
                $table->unsignedBigInteger('order_item_id')->nullable()->after('order_id');
            }

            if (! Schema::hasColumn('order_artworks', 'product_id')) {
                $table->unsignedBigInteger('product_id')->nullable()->after('order_item_id');
            }

            if (! Schema::hasColumn('order_artworks', 'cart_item_id')) {
                $table->string('cart_item_id')->nullable()->after('product_id');
            }

            if (! Schema::hasColumn('order_artworks', 'file_path')) {
                $table->string('file_path')->nullable()->after('cart_item_id');
            }

            if (! Schema::hasColumn('order_artworks', 'original_name')) {
                $table->string('original_name')->nullable()->after('file_path');
            }

            if (! Schema::hasColumn('order_artworks', 'mime_type')) {
                $table->string('mime_type')->nullable()->after('original_name');
            }

            if (! Schema::hasColumn('order_artworks', 'file_size')) {
                $table->unsignedBigInteger('file_size')->nullable()->after('mime_type');
            }

            if (! Schema::hasColumn('order_artworks', 'no_artwork')) {
                $table->tinyInteger('no_artwork')->default(0)->after('file_size');
            }

            if (! Schema::hasColumn('order_artworks', 'print_text')) {
                $table->text('print_text')->nullable()->after('no_artwork');
            }

            if (! Schema::hasColumn('order_artworks', 'font_option')) {
                $table->string('font_option')->nullable()->after('print_text');
            }

            if (! Schema::hasColumn('order_artworks', 'font_other')) {
                $table->string('font_other')->nullable()->after('font_option');
            }

            if (! Schema::hasColumn('order_artworks', 'template_id')) {
                $table->unsignedBigInteger('template_id')->nullable()->after('font_other');
            }

            if (! Schema::hasColumn('order_artworks', 'status')) {
                $table->string('status', 50)->default('pending')->after('template_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('order_artworks', function (Blueprint $table) {
            $columns = [
                'order_id',
                'order_item_id',
                'product_id',
                'cart_item_id',
                'file_path',
                'original_name',
                'mime_type',
                'file_size',
                'no_artwork',
                'print_text',
                'font_option',
                'font_other',
                'template_id',
                'status',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('order_artworks', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

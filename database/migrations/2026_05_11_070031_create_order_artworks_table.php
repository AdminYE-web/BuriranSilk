<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('order_artworks')) {
            Schema::create('order_artworks', function (Blueprint $table) {
                $table->id('order_artwork_id');

                $table->unsignedBigInteger('order_id');
                $table->unsignedBigInteger('order_item_id')->nullable();
                $table->unsignedBigInteger('product_id')->nullable();

                $table->string('cart_item_id')->nullable();

                $table->string('file_path')->nullable();
                $table->string('original_name')->nullable();
                $table->string('mime_type')->nullable();
                $table->unsignedBigInteger('file_size')->nullable();

                $table->tinyInteger('no_artwork')->default(0);
                $table->text('print_text')->nullable();
                $table->string('font_option')->nullable();
                $table->string('font_other')->nullable();
                $table->unsignedBigInteger('template_id')->nullable();

                $table->string('status', 50)->default('pending');

                $table->timestamps();

                $table->foreign('order_id')
                    ->references('order_id')
                    ->on('orders')
                    ->onDelete('cascade');

                $table->foreign('order_item_id')
                    ->references('order_item_id')
                    ->on('order_items')
                    ->onDelete('set null');

                $table->foreign('product_id')
                    ->references('product_id')
                    ->on('products')
                    ->onDelete('set null');

                $table->foreign('template_id')
                    ->references('template_id')
                    ->on('product_artwork_templates')
                    ->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('order_artworks')) {
            Schema::dropIfExists('order_artworks');
        }
    }
};

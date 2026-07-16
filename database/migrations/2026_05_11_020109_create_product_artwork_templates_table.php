<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('product_artwork_templates')) {
            Schema::create('product_artwork_templates', function (Blueprint $table) {
                $table->id('template_id');

                $table->unsignedBigInteger('product_id');

                $table->string('template_name', 255);
                $table->string('image_path', 255)->nullable();

                $table->integer('sort_order')->default(0);
                $table->tinyInteger('is_active')->default(1);

                $table->timestamps();

                $table->foreign('product_id')
                    ->references('product_id')
                    ->on('products')
                    ->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('product_artwork_templates')) {
            Schema::dropIfExists('product_artwork_templates');
        }
    }
};

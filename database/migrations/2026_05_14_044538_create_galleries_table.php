<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('galleries', function (Blueprint $table) {
            $table->id('gallery_id');

            $table->string('title');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('material_id')->nullable();

            $table->text('purpose')->nullable();
            $table->date('gallery_date')->nullable();

            $table->string('cover_image')->nullable();

            $table->tinyInteger('is_active')->default(1);
            $table->integer('sort_order')->default(0);

            $table->timestamps();

            $table->foreign('category_id')
                ->references('category_id')
                ->on('categories')
                ->onDelete('set null');

            $table->foreign('material_id')
                ->references('material_id')
                ->on('materials')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};

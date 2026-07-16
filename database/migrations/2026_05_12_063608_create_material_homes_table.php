<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('material_homes', function (Blueprint $table) {
            $table->id('material_home_id');

            $table->unsignedBigInteger('material_id')->nullable();

            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();

            $table->tinyInteger('is_active')->default(1);
            $table->integer('sort_order')->default(0);

            $table->timestamps();

            $table->foreign('material_id')
                ->references('material_id')
                ->on('materials')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_homes');
    }
};

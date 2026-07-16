<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('option_images', function (Blueprint $table) {
            $table->id('image_id');

            $table->unsignedBigInteger('option_id');

            $table->string('image_path');
            $table->string('image_alt')->nullable();

            $table->boolean('is_main')->default(false);
            $table->integer('sort_order')->default(0);

            $table->timestamps();

            $table->foreign('option_id')
                ->references('option_id')
                ->on('product_options')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('option_images');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_option_variants', function (Blueprint $table) {
            $table->id('variant_id');

            $table->unsignedBigInteger('option_id');

            $table->string('variant_name')->nullable(); // เช่น Black, White, Red
            $table->string('color_code', 20)->nullable(); // เช่น #000000
            $table->string('image_path')->nullable();

            $table->decimal('additional_price', 10, 2)->default(0);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->foreign('option_id')
                ->references('option_id')
                ->on('product_options')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_option_variants');
    }
};

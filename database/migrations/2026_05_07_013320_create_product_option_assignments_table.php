<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_option_assignments', function (Blueprint $table) {
            $table->id('assignment_id');

            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('option_id');

            $table->integer('sort_order')->default(0);
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->foreign('product_id')
                ->references('product_id')
                ->on('products')
                ->onDelete('cascade');

            $table->foreign('option_id')
                ->references('option_id')
                ->on('product_options')
                ->onDelete('cascade');

            $table->unique(['product_id', 'option_id'], 'unique_product_option_assignment');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_option_assignments');
    }
};

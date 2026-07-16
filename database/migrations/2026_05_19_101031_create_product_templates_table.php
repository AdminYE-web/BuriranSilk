<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_templates', function (Blueprint $table) {
            $table->id('template_id');

            $table->unsignedBigInteger('product_id');

            $table->string('language', 20)->default('pt');
            $table->string('template_size')->nullable();

            $table->string('file_path');
            $table->string('original_name')->nullable();
            $table->string('file_type', 20)->nullable();

            $table->tinyInteger('is_active')->default(1);

            $table->timestamps();

            $table->foreign('product_id')
                ->references('product_id')
                ->on('products')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_templates');
    }
};

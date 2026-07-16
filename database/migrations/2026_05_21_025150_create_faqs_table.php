<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id('faq_id');

            $table->unsignedBigInteger('product_id')->nullable();

            $table->string('language', 20)->default('pt');

            $table->string('question');
            $table->longText('answer')->nullable();

            $table->tinyInteger('show_main')->default(0);
            $table->tinyInteger('show_product')->default(0);

            $table->enum('status', ['show', 'hide'])->default('show');

            $table->integer('sort_order')->default(0);

            $table->timestamps();

            $table->foreign('product_id')
                ->references('product_id')
                ->on('products')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};

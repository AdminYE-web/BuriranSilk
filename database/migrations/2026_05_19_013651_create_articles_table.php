<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id('article_id');

            $table->string('title');
            $table->string('category')->nullable();
            $table->date('article_date')->nullable();

            $table->longText('detail')->nullable();

            $table->string('cover_image')->nullable();

            $table->tinyInteger('is_active')->default(1);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};

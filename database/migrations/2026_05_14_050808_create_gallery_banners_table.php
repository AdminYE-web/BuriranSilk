<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gallery_banners', function (Blueprint $table) {
            $table->id('gallery_banner_id');

            $table->string('title')->nullable();
            $table->string('link_url')->nullable();

            $table->string('image_pc')->nullable();
            $table->string('image_mobile')->nullable();

            $table->integer('sort_order')->default(0);
            $table->tinyInteger('is_active')->default(1);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gallery_banners');
    }
};

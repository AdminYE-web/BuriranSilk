<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_banners', function (Blueprint $table) {
            $table->id('home_banner_id');

            $table->string('title')->nullable();
            $table->string('link_url')->nullable();

            // รูป 2 ขนาด
            $table->string('image_pc')->nullable();
            $table->string('image_mobile')->nullable();

            $table->tinyInteger('is_active')->default(1);
            $table->integer('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_banners');
    }
};

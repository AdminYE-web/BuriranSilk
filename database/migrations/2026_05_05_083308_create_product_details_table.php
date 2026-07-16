<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_details', function (Blueprint $table) {
            $table->id('product_detail_id');

            $table->unsignedBigInteger('product_id');

            $table->string('sample_image')->nullable();
            // รูปตัวอย่าง 1 รูป

            $table->longText('detail_content')->nullable();
            // เก็บ HTML จาก CKEditor

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->foreign('product_id')
                ->references('product_id')
                ->on('products')
                ->onDelete('cascade');

            $table->unique('product_id');
            // 1 product มี detail ได้ 1 record
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_details');
    }
};

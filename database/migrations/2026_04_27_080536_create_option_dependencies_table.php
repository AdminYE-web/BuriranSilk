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
        Schema::create('option_dependencies', function (Blueprint $table) {
            $table->id('dependency_id');

            $table->unsignedBigInteger('parent_option_id');
            // เช่น แบบนุ่ม

            $table->unsignedBigInteger('child_option_id');
            // เช่น แนวนอนของแบบนุ่ม

            $table->timestamps();

            $table->foreign('parent_option_id')
                ->references('option_id')
                ->on('product_options')
                ->onDelete('cascade');

            $table->foreign('child_option_id')
                ->references('option_id')
                ->on('product_options')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('option_dependencies');
    }
};

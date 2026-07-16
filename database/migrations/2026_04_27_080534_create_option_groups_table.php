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
        Schema::create('option_groups', function (Blueprint $table) {
            $table->id('option_group_id');

            $table->string('group_code');
            // เช่น print_surface, delivery_time, pouch_type

            $table->string('group_name');
            // เช่น พื้นผิวการพิมพ์

            $table->boolean('is_required')->default(false);
            // บังคับเลือกไหม

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('option_groups');
    }
};

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
        Schema::create('product_options', function (Blueprint $table) {
            $table->id('option_id');

            $table->unsignedBigInteger('option_group_id');

            $table->string('option_code')->nullable();
            $table->string('option_name');

            $table->decimal('additional_price', 10, 2)->default(0);
            // ราคาบวกเพิ่มต่อชิ้น หรือบวกเพิ่มต่อ order แล้วแต่ price_type

            $table->enum('price_type', ['per_item', 'per_order'])->default('per_item');
            // per_item = เพิ่มต่อชิ้น
            // per_order = เพิ่มครั้งเดียวต่อออเดอร์

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->foreign('option_group_id')
                ->references('option_group_id')
                ->on('option_groups')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_options');
    }
};

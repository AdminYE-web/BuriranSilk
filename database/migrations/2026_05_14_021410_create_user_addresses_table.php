<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id('user_address_id');

            $table->unsignedBigInteger('user_id');

            // shipping หรือ billing
            $table->string('address_type', 20);

            $table->string('label');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone', 50);

            $table->string('company_name')->nullable();
            $table->string('address');
            $table->string('apartment')->nullable();

            $table->string('country');
            $table->string('city');
            $table->string('state');
            $table->string('zip_code', 50);

            $table->tinyInteger('is_main')->default(0);
            $table->tinyInteger('is_active')->default(1);

            $table->timestamps();

            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');

            $table->index(['user_id', 'address_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};

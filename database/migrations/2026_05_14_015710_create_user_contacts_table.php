<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_contacts', function (Blueprint $table) {
            $table->id('user_contact_id');

            $table->unsignedBigInteger('user_id');

            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone', 50);
            $table->string('email');

            $table->tinyInteger('is_main')->default(0);
            $table->tinyInteger('receive_email')->default(0);
            $table->tinyInteger('is_active')->default(1);

            $table->timestamps();

            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_contacts');
    }
};

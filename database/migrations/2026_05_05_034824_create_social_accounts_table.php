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
        Schema::create('social_accounts', function (Blueprint $table) {
            $table->id('social_account_id');

            $table->unsignedBigInteger('user_id');
            $table->string('provider', 50);
            $table->string('provider_id');
            $table->string('provider_email')->nullable();
            $table->string('provider_name')->nullable();
            $table->text('avatar')->nullable();

            $table->timestamps();

            $table->unique(['provider', 'provider_id']);

            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_accounts');
    }
};

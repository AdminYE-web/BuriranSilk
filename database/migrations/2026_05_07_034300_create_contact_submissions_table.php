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
        Schema::create('contact_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('contact_method', 20);       // whatsapp, line, phone
            $table->string('subject', 100);              // payment, quote, support, order
            $table->string('name', 255);
            $table->string('email', 255);
            $table->string('line_id', 100)->nullable();
            $table->string('country_code', 10)->nullable();
            $table->string('phone', 30)->nullable();
            $table->text('message');
            $table->string('attachment_path')->nullable();
            $table->string('attachment_original_name')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_submissions');
    }
};

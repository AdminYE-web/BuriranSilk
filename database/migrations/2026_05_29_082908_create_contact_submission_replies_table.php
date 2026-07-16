<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_submission_replies', function (Blueprint $table) {
            $table->id('reply_id');

            $table->unsignedBigInteger('contact_submission_id');

            $table->unsignedBigInteger('admin_user_id')->nullable();
            $table->string('admin_name')->nullable();
            $table->string('admin_email')->nullable();

            $table->string('reply_subject')->nullable();
            $table->longText('reply_message');

            $table->string('attachment_path')->nullable();
            $table->string('attachment_original_name')->nullable();

            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->foreign('contact_submission_id')
                ->references('id')
                ->on('contact_submissions')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_submission_replies');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_details', function (Blueprint $table) {
            $table->string('specification_image')->nullable()->after('sample_image');
            $table->json('specification_content')->nullable()->after('detail_content');
        });
    }

    public function down(): void
    {
        Schema::table('product_details', function (Blueprint $table) {
            $table->dropColumn([
                'specification_image',
                'specification_content',
            ]);
        });
    }
};

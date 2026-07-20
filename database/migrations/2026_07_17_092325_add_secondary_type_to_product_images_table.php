<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE product_images
            MODIFY image_type
            ENUM('main', 'secondary', 'gallery')
            NOT NULL DEFAULT 'main'
        ");
    }

    public function down(): void
    {
        DB::table('product_images')
            ->where('image_type', 'secondary')
            ->update([
                'image_type' => 'gallery',
            ]);

        DB::statement("
            ALTER TABLE product_images
            MODIFY image_type
            ENUM('main', 'gallery')
            NOT NULL DEFAULT 'main'
        ");
    }
};
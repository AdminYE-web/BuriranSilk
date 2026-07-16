<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE product_images MODIFY image_type ENUM('main', 'gallery') DEFAULT 'main'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE product_images MODIFY image_type ENUM('main') DEFAULT 'main'");
    }
};

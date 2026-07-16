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
    Schema::table('material_homes', function (Blueprint $table) {
        $table->unsignedTinyInteger('product_type')->default(1)->after('material_id');
    });
}

public function down(): void
{
    Schema::table('material_homes', function (Blueprint $table) {
        $table->dropColumn('product_type');
    });
}
};

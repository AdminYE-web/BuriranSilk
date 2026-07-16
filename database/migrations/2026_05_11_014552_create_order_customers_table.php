<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_customers', function (Blueprint $table) {
            $table->id('order_customer_id');

            $table->unsignedBigInteger('order_id');

            /*
            |--------------------------------------------------------------------------
            | 1. Personal Information
            |--------------------------------------------------------------------------
            */
            $table->string('personal_first_name')->nullable();
            $table->string('personal_last_name')->nullable();
            $table->string('personal_phone')->nullable();
            $table->string('personal_email')->nullable();

            /*
            |--------------------------------------------------------------------------
            | 2. Shipping Address
            |--------------------------------------------------------------------------
            */
            $table->string('shipping_postcode')->nullable();
            $table->string('shipping_province')->nullable();
            $table->string('shipping_district')->nullable();
            $table->string('shipping_subdistrict')->nullable();
            $table->string('shipping_building_room')->nullable();
            $table->text('shipping_address')->nullable();

            /*
            |--------------------------------------------------------------------------
            | 3. Billing Address
            |--------------------------------------------------------------------------
            */
            $table->string('billing_first_name')->nullable();
            $table->string('billing_last_name')->nullable();
            $table->string('billing_phone')->nullable();
            $table->string('billing_email')->nullable();

            $table->string('billing_postcode')->nullable();
            $table->string('billing_province')->nullable();
            $table->string('billing_district')->nullable();
            $table->string('billing_subdistrict')->nullable();
            $table->string('billing_building_room')->nullable();
            $table->text('billing_address')->nullable();

            $table->timestamps();

            $table->foreign('order_id')
                ->references('order_id')
                ->on('orders')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_customers');
    }
};

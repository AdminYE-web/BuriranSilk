<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->uuid('checkout_token')->nullable()->unique()->after('order_no');
            $table->string('info_method')->nullable()->after('payment_status');
            $table->text('signature_text')->nullable()->after('info_method');
            $table->string('delivery_option')->nullable()->after('signature_text');
            $table->boolean('publish_website')->nullable()->after('delivery_option');
            $table->boolean('newsletter')->default(false)->after('publish_website');
            $table->text('notes')->nullable()->after('newsletter');
            $table->json('checkout_data')->nullable()->after('notes');
            $table->timestamp('confirmation_email_sent_at')->nullable()->after('checkout_data');
        });

        Schema::table('order_customers', function (Blueprint $table) {
            $table->string('customer_type', 20)->nullable()->after('order_id');
            $table->string('personal_name')->nullable()->after('customer_type');
            $table->string('personal_name_kana')->nullable()->after('personal_name');
            $table->string('company_name')->nullable()->after('personal_name_kana');
            $table->string('company_name_kana')->nullable()->after('company_name');
            $table->boolean('same_as_customer')->default(true)->after('personal_email');
            $table->string('shipping_name')->nullable()->after('same_as_customer');
            $table->string('shipping_name_kana')->nullable()->after('shipping_name');
            $table->string('shipping_city')->nullable()->after('shipping_province');
            $table->string('shipping_area')->nullable()->after('shipping_city');
            $table->string('billing_address_type', 30)->nullable()->after('shipping_address');
            $table->string('billing_name')->nullable()->after('billing_address_type');
            $table->string('billing_name_kana')->nullable()->after('billing_name');
            $table->string('billing_city')->nullable()->after('billing_province');
            $table->string('billing_area')->nullable()->after('billing_city');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->json('configuration')->nullable()->after('custom_colors');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('configuration');
        });

        Schema::table('order_customers', function (Blueprint $table) {
            $table->dropColumn([
                'customer_type',
                'personal_name',
                'personal_name_kana',
                'company_name',
                'company_name_kana',
                'same_as_customer',
                'shipping_name',
                'shipping_name_kana',
                'shipping_city',
                'shipping_area',
                'billing_address_type',
                'billing_name',
                'billing_name_kana',
                'billing_city',
                'billing_area',
            ]);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique(['checkout_token']);
            $table->dropColumn([
                'checkout_token',
                'info_method',
                'signature_text',
                'delivery_option',
                'publish_website',
                'newsletter',
                'notes',
                'checkout_data',
                'confirmation_email_sent_at',
            ]);
        });
    }
};

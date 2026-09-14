<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customer_registrations', function (Blueprint $table) {
            if (!Schema::hasColumn('customer_registrations', 'brand_name')) {
                $table->string('brand_name')->nullable()->after('customer_name');
            }
            if (!Schema::hasColumn('customer_registrations', 'identity_type')) {
                $table->string('identity_type', 20)->nullable()->default('KTP')->after('token');
            }
            if (!Schema::hasColumn('customer_registrations', 'identity_number')) {
                $table->string('identity_number', 50)->nullable()->after('identity_type');
            }
            if (!Schema::hasColumn('customer_registrations', 'phone_telp')) {
                $table->string('phone_telp', 50)->nullable()->after('gender');
            }
            if (!Schema::hasColumn('customer_registrations', 'services_selected')) {
                $table->json('services_selected')->nullable()->after('addons');
            }
            if (!Schema::hasColumn('customer_registrations', 'subscription_period')) {
                $table->string('subscription_period', 100)->nullable()->after('services_selected');
            }
            if (!Schema::hasColumn('customer_registrations', 'billing_name')) {
                $table->string('billing_name')->nullable()->after('subscription_period');
            }
            if (!Schema::hasColumn('customer_registrations', 'billing_address')) {
                $table->text('billing_address')->nullable()->after('billing_name');
            }
            if (!Schema::hasColumn('customer_registrations', 'billing_phone')) {
                $table->string('billing_phone', 50)->nullable()->after('billing_address');
            }
            if (!Schema::hasColumn('customer_registrations', 'billing_mobile')) {
                $table->string('billing_mobile', 50)->nullable()->after('billing_phone');
            }
        });
    }

    public function down(): void
    {
        Schema::table('customer_registrations', function (Blueprint $table) {
            $table->dropColumn([
                'brand_name',
                'identity_type',
                'identity_number',
                'phone_telp',
                'services_selected',
                'subscription_period',
                'billing_name',
                'billing_address',
                'billing_phone',
                'billing_mobile',
            ]);
        });
    }
};

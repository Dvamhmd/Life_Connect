<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customer_registrations', function (Blueprint $table) {
            // Index for sorting and composite index for status filtering + sorting
            $table->index('submitted_at', 'idx_cust_reg_submitted_at');
            $table->index(['status', 'submitted_at'], 'idx_cust_reg_status_submitted_at');
            
            // Indexes for fast searching
            $table->index('customer_name', 'idx_cust_reg_customer_name');
            $table->index('phone_wa', 'idx_cust_reg_phone_wa');
            $table->index('sales_name', 'idx_cust_reg_sales_name');
            $table->index('village', 'idx_cust_reg_village');
        });
    }

    public function down(): void
    {
        Schema::table('customer_registrations', function (Blueprint $table) {
            $table->dropIndex('idx_cust_reg_submitted_at');
            $table->dropIndex('idx_cust_reg_status_submitted_at');
            $table->dropIndex('idx_cust_reg_customer_name');
            $table->dropIndex('idx_cust_reg_phone_wa');
            $table->dropIndex('idx_cust_reg_sales_name');
            $table->dropIndex('idx_cust_reg_village');
        });
    }
};

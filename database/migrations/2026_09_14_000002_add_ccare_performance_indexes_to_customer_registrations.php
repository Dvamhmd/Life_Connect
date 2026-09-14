<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customer_registrations', function (Blueprint $table) {
            // Index for filled_at sorting and composite index for status filtering + filled_at sorting
            $table->index('filled_at', 'idx_cust_reg_filled_at');
            $table->index(['status', 'filled_at'], 'idx_cust_reg_status_filled_at');
            
            // Index for NIK searching
            $table->index('nik', 'idx_cust_reg_nik');
        });
    }

    public function down(): void
    {
        Schema::table('customer_registrations', function (Blueprint $table) {
            $table->dropIndex('idx_cust_reg_filled_at');
            $table->dropIndex('idx_cust_reg_status_filled_at');
            $table->dropIndex('idx_cust_reg_nik');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customer_registrations', function (Blueprint $table) {
            $table->index(['sales_user_id', 'submitted_at'], 'idx_cust_reg_sales_user_submitted');
            $table->index('regency', 'idx_cust_reg_regency');
        });

        Schema::table('registration_progress_logs', function (Blueprint $table) {
            $table->index(['to_status', 'duration_seconds'], 'idx_prog_logs_status_duration');
        });
    }

    public function down(): void
    {
        Schema::table('customer_registrations', function (Blueprint $table) {
            $table->dropIndex('idx_cust_reg_sales_user_submitted');
            $table->dropIndex('idx_cust_reg_regency');
        });

        Schema::table('registration_progress_logs', function (Blueprint $table) {
            $table->dropIndex('idx_prog_logs_status_duration');
        });
    }
};

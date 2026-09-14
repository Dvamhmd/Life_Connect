<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->index('created_at', 'idx_audit_logs_created_at');
            $table->index(['action', 'created_at'], 'idx_audit_logs_action_created');
            $table->index(['module', 'created_at'], 'idx_audit_logs_module_created');
            $table->index('user_name', 'idx_audit_logs_user_name');
            $table->index('ip_address', 'idx_audit_logs_ip_address');
        });
    }

    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropIndex('idx_audit_logs_created_at');
            $table->dropIndex('idx_audit_logs_action_created');
            $table->dropIndex('idx_audit_logs_module_created');
            $table->dropIndex('idx_audit_logs_user_name');
            $table->dropIndex('idx_audit_logs_ip_address');
        });
    }
};

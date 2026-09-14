<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registration_progress_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_registration_id')->constrained('customer_registrations')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('actor_name');
            $table->string('actor_role');
            $table->string('from_status')->nullable();
            $table->string('to_status');
            $table->text('notes')->nullable();
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registration_progress_logs');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('sales_am_id')->nullable()->index();
            $table->string('title');
            $table->text('message');
            $table->string('type', 50)->default('general'); // survey_verified, registration_filled, registration_approved, registration_revision
            $table->foreignId('customer_registration_id')->nullable()->constrained('customer_registrations')->nullOnDelete();
            $table->string('link')->nullable();
            $table->string('action_type', 50)->nullable(); // share_whatsapp, review_detail, revise_data
            $table->boolean('is_read')->default(false);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};

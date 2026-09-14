<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('registration_code', 50)->unique()->index();
            $table->foreignId('sales_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('sales_am_id')->nullable()->index();
            $table->string('sales_name')->nullable();
            
            // Survey Information (filled by Sales)
            $table->string('customer_name');
            $table->string('phone_wa');
            $table->string('email')->nullable();
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('province');
            $table->string('regency');
            $table->string('district');
            $table->string('village');
            $table->text('address_detail')->nullable();
            $table->string('selfie_sales_path')->nullable();
            
            // Registration status
            // Flow: submitted -> verified -> filled -> approved / revision
            $table->enum('status', ['submitted', 'verified', 'filled', 'approved', 'revision'])->default('submitted')->index();
            $table->string('token', 64)->unique()->index();

            // Customer Self-Registration Information (filled by Customer)
            $table->string('nik', 20)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('gender', 20)->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('emergency_contact_relation')->nullable();
            
            $table->foreignId('package_id')->nullable()->constrained('subscription_packages')->nullOnDelete();
            $table->json('addons')->nullable();
            $table->string('billing_method')->nullable();
            $table->string('billing_email')->nullable();
            $table->string('billing_cycle')->default('Bulanan (Setiap Tgl 1)');
            
            // Customer uploaded documents & signature
            $table->string('ktp_photo_path')->nullable();
            $table->string('house_photo_path')->nullable();
            $table->text('signature_path')->nullable(); // stored as file path or base64 data
            $table->boolean('terms_agreed')->default(false);

            // Rejection / Revision details from C-Care
            $table->text('rejection_notes')->nullable();
            $table->string('rejection_category')->nullable();

            // Milestones & SLA Tracking Timestamps
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('filled_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('revision_at')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_registrations');
    }
};

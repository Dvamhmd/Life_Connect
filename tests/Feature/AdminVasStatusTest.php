<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\CustomerRegistration;
use App\Models\AuditLog;
use App\Models\RegistrationProgressLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminVasStatusTest extends TestCase
{
    use RefreshDatabase;

    private function createRegistration(array $overrides = []): CustomerRegistration
    {
        return CustomerRegistration::create(array_merge([
            'registration_code' => 'REG-TEST-' . rand(100, 999),
            'sales_name' => 'Sales AM Demo',
            'sales_am_id' => 'AM-101',
            'customer_name' => 'Budi Santoso',
            'phone_wa' => '081234567890',
            'latitude' => -7.7956,
            'longitude' => 110.3695,
            'province' => 'DI Yogyakarta',
            'regency' => 'Sleman',
            'district' => 'Depok',
            'village' => 'Caturtunggal',
            'status' => 'submitted',
            'token' => 'test-token-' . rand(1000, 9999),
            'submitted_at' => now(),
        ], $overrides));
    }

    public function test_admin_vas_can_change_registration_status_to_approved()
    {
        $admin = User::factory()->create([
            'role' => 'admin_vas',
            'status' => 'active',
        ]);

        $sales = User::factory()->create([
            'role' => 'sales',
            'sales_id' => 'AM-999',
            'status' => 'active',
        ]);

        $registration = $this->createRegistration([
            'sales_user_id' => $sales->id,
            'sales_am_id' => $sales->sales_id,
            'sales_name' => $sales->name,
            'status' => 'submitted',
        ]);

        $response = $this->actingAs($admin)->post(route('vas.registrations.update-status', $registration->id), [
            'status' => 'approved',
            'notes' => 'Disetujui langsung oleh Admin VAS.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $registration->refresh();
        $this->assertEquals('approved', $registration->status);
        $this->assertNotNull($registration->approved_at);

        // Verify Progress Log
        $this->assertDatabaseHas('registration_progress_logs', [
            'customer_registration_id' => $registration->id,
            'actor_role' => 'Admin VAS',
            'from_status' => 'submitted',
            'to_status' => 'approved',
        ]);

        // Verify Audit Log
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'UPDATE_REGISTRATION_STATUS',
            'module' => 'AdminVAS',
            'target_id' => $registration->id,
        ]);
    }

    public function test_admin_vas_can_change_registration_status_to_revision()
    {
        $admin = User::factory()->create([
            'role' => 'admin_vas',
            'status' => 'active',
        ]);

        $sales = User::factory()->create([
            'role' => 'sales',
            'sales_id' => 'AM-888',
            'status' => 'active',
        ]);

        $registration = $this->createRegistration([
            'sales_user_id' => $sales->id,
            'sales_am_id' => $sales->sales_id,
            'sales_name' => $sales->name,
            'status' => 'filled',
            'filled_at' => now(),
        ]);

        $response = $this->actingAs($admin)->post(route('vas.registrations.update-status', $registration->id), [
            'status' => 'revision',
            'rejection_category' => 'Foto KTP Buram / Tidak Jelas',
            'notes' => 'Mohon foto KTP diupload ulang.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $registration->refresh();
        $this->assertEquals('revision', $registration->status);
        $this->assertEquals('Foto KTP Buram / Tidak Jelas', $registration->rejection_category);
        $this->assertEquals('Mohon foto KTP diupload ulang.', $registration->rejection_notes);
        $this->assertNotNull($registration->revision_at);
    }

    public function test_non_admin_vas_cannot_change_status_via_vas_route()
    {
        $sales = User::factory()->create([
            'role' => 'sales',
            'status' => 'active',
        ]);

        $registration = $this->createRegistration([
            'status' => 'submitted',
        ]);

        $response = $this->actingAs($sales)->post(route('vas.registrations.update-status', $registration->id), [
            'status' => 'approved',
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_sales_cannot_change_registration_status()
    {
        $adminSales = User::factory()->create([
            'role' => 'admin_sales',
            'status' => 'active',
        ]);

        $registration = $this->createRegistration([
            'status' => 'submitted',
        ]);

        $response = $this->actingAs($adminSales)->post(route('vas.registrations.update-status', $registration->id), [
            'status' => 'approved',
        ]);

        $response->assertStatus(403);
    }
}

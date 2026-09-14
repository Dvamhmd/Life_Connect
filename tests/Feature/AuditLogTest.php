<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_audit_logs_page_renders_successfully()
    {
        $admin = User::factory()->create([
            'role' => 'admin_vas',
            'status' => 'active',
        ]);

        AuditLog::create([
            'user_id' => $admin->id,
            'user_name' => $admin->name,
            'user_role' => $admin->role,
            'action' => 'UPDATE_USER',
            'module' => 'UserManagement',
            'target_type' => 'User',
            'target_id' => 1,
            'description' => 'Admin VAS updated user data',
            'old_values' => ['name' => 'Old Name'],
            'new_values' => ['name' => 'New Name'],
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0',
            'created_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('vas.audit-logs'));
        $response->assertStatus(200);
        $response->assertSee('UserManagement');
        $response->assertSee('Admin VAS updated user data');
    }

    public function test_audit_logs_filters_and_pagination()
    {
        $admin = User::factory()->create([
            'role' => 'admin_vas',
            'status' => 'active',
        ]);

        AuditLog::create([
            'user_id' => $admin->id,
            'user_name' => 'Demo User',
            'user_role' => 'opj',
            'action' => 'VERIFY_SURVEY',
            'module' => 'OPJ',
            'description' => 'Survey verified test',
            'created_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('vas.audit-logs', [
            'module' => 'OPJ',
            'action' => 'VERIFY_SURVEY',
            'per_page' => 25,
            'search' => 'Survey',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Survey verified test');
    }
}

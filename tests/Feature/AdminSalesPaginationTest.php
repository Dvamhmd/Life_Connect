<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\CustomerRegistration;
use App\Models\SubscriptionPackage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSalesPaginationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_sales_pagination_renders_successfully()
    {
        $adminUser = User::factory()->create([
            'role' => 'admin_sales',
            'status' => 'active',
        ]);

        $salesUser = User::factory()->create([
            'role' => 'sales',
            'sales_id' => 'AM-88',
            'name' => 'Sales Demo',
            'status' => 'active',
        ]);

        $package = SubscriptionPackage::create([
            'code' => 'LM-100',
            'name' => 'LifeMedia Fast 100 Mbps',
            'speed' => '100 Mbps',
            'price' => 500000,
            'is_active' => true,
        ]);

        CustomerRegistration::create([
            'registration_code' => 'REG-2026-003',
            'sales_user_id' => $salesUser->id,
            'sales_name' => $salesUser->name,
            'sales_am_id' => $salesUser->sales_id,
            'customer_name' => 'Hendra Setiawan',
            'phone_wa' => '081345678901',
            'package_id' => $package->id,
            'latitude' => -7.7956,
            'longitude' => 110.3695,
            'province' => 'DI Yogyakarta',
            'regency' => 'Sleman',
            'district' => 'Depok',
            'village' => 'Caturtunggal',
            'status' => 'submitted',
            'token' => 'token9876543210fedcba',
            'submitted_at' => now(),
        ]);

        $response = $this->actingAs($adminUser)->get(route('admin-sales.dashboard', [
            'per_page' => 10,
            'status' => 'all',
        ]));

        $response->assertStatus(200);
        $response->assertViewHas('registrations');
        $response->assertViewHas('total');
        $response->assertViewHas('salesPerformance');
        $response->assertViewHas('perPage', 10);
        $response->assertSee('Hendra Setiawan');
        $response->assertSee('REG-2026-003');
    }

    public function test_admin_sales_pagination_with_per_page_options()
    {
        $adminUser = User::factory()->create([
            'role' => 'admin_sales',
            'status' => 'active',
        ]);

        $response = $this->actingAs($adminUser)->get(route('admin-sales.dashboard', [
            'per_page' => 25,
            'status' => 'submitted',
        ]));

        $response->assertStatus(200);
        $response->assertViewHas('perPage', 25);
    }
}

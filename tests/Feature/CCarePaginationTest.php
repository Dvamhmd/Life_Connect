<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\CustomerRegistration;
use App\Models\SubscriptionPackage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CCarePaginationTest extends TestCase
{
    use RefreshDatabase;

    public function test_ccare_customers_pagination_renders_successfully()
    {
        $ccareUser = User::factory()->create([
            'role' => 'c_care',
            'status' => 'active',
        ]);

        $package = SubscriptionPackage::create([
            'code' => 'LM-50',
            'name' => 'LifeMedia Fast 50 Mbps',
            'speed' => '50 Mbps',
            'price' => 300000,
            'is_active' => true,
        ]);

        CustomerRegistration::create([
            'registration_code' => 'REG-2026-002',
            'sales_name' => 'Sales Budi',
            'sales_am_id' => 'AM002',
            'customer_name' => 'Siti Nurhaliza',
            'nik' => '3471012345678901',
            'phone_wa' => '081298765432',
            'package_id' => $package->id,
            'latitude' => -7.7956,
            'longitude' => 110.3695,
            'province' => 'DI Yogyakarta',
            'regency' => 'Kota Yogyakarta',
            'district' => 'Gondomanan',
            'village' => 'Prawirodirjan',
            'status' => 'filled',
            'token' => 'tokenabcdef1234567890',
            'submitted_at' => now()->subDay(),
            'filled_at' => now(),
        ]);

        $response = $this->actingAs($ccareUser)->get(route('ccare.index', [
            'per_page' => 10,
            'status' => 'filled',
        ]));

        $response->assertStatus(200);
        $response->assertViewHas('registrations');
        $response->assertViewHas('stats');
        $response->assertViewHas('perPage', 10);
        $response->assertSee('Siti Nurhaliza');
        $response->assertSee('3471012345678901');
        $response->assertSee('LifeMedia Fast 50 Mbps');
    }

    public function test_ccare_customers_pagination_with_per_page_options()
    {
        $ccareUser = User::factory()->create([
            'role' => 'c_care',
            'status' => 'active',
        ]);

        $response = $this->actingAs($ccareUser)->get(route('ccare.index', [
            'per_page' => 50,
            'status' => 'all',
        ]));

        $response->assertStatus(200);
        $response->assertViewHas('perPage', 50);
    }
}

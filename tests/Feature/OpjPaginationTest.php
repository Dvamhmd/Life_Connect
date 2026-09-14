<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\CustomerRegistration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OpjPaginationTest extends TestCase
{
    use RefreshDatabase;

    public function test_opj_surveys_pagination_renders_successfully()
    {
        $opjUser = User::factory()->create([
            'role' => 'opj',
            'status' => 'active',
        ]);

        CustomerRegistration::create([
            'registration_code' => 'REG-2026-001',
            'sales_name' => 'Sales Demo',
            'sales_am_id' => 'AM001',
            'customer_name' => 'Budi Santoso',
            'phone_wa' => '081234567890',
            'latitude' => -7.7956,
            'longitude' => 110.3695,
            'province' => 'DI Yogyakarta',
            'regency' => 'Kota Yogyakarta',
            'district' => 'Danurejan',
            'village' => 'Bausasran',
            'status' => 'submitted',
            'token' => 'token1234567890abcdef',
            'submitted_at' => now(),
        ]);

        $response = $this->actingAs($opjUser)->get(route('opj.index', [
            'per_page' => 10,
            'status' => 'all',
        ]));

        $response->assertStatus(200);
        $response->assertViewHas('registrations');
        $response->assertViewHas('stats');
        $response->assertViewHas('perPage', 10);
        $response->assertSee('Budi Santoso');
        $response->assertSee('REG-2026-001');
    }

    public function test_opj_surveys_pagination_with_per_page_options()
    {
        $opjUser = User::factory()->create([
            'role' => 'opj',
            'status' => 'active',
        ]);

        $response = $this->actingAs($opjUser)->get(route('opj.index', [
            'per_page' => 25,
            'status' => 'submitted',
        ]));

        $response->assertStatus(200);
        $response->assertViewHas('perPage', 25);
    }
}

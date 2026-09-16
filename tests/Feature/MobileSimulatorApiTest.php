<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class MobileSimulatorApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_online_regions_provinces(): void
    {
        $response = $this->getJson('/api/regions/online?type=provinces');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'source',
                     'type',
                     'data' => [
                         '*' => ['id', 'name', 'raw_name']
                     ]
                 ]);
    }

    public function test_reverse_geocode_endpoint(): void
    {
        // Test Sleman DIY coordinates
        $response = $this->getJson('/api/reverse-geocode?lat=-7.761352&lng=110.385412');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'province',
                         'regency',
                         'district',
                         'village',
                         'road',
                     ]
                 ]);

        $data = $response->json('data');
        $this->assertNotEmpty($data['province']);
        $this->assertNotEmpty($data['regency']);
    }

    public function test_online_regions_cascading(): void
    {
        // 1. Fetch Provinces
        $provRes = $this->getJson('/api/regions/online?type=provinces');
        $provRes->assertStatus(200);
        $provinces = $provRes->json('data');
        $this->assertNotEmpty($provinces);

        // Find DIY (ID: 34) or first province
        $diy = collect($provinces)->firstWhere('id', '34') ?? $provinces[0];
        $this->assertNotNull($diy);

        // 2. Fetch Regencies for province
        $regRes = $this->getJson('/api/regions/online?type=regencies&parent_id=' . $diy['id']);
        $regRes->assertStatus(200);
        $regencies = $regRes->json('data');
        $this->assertNotEmpty($regencies);

        // 3. Fetch Districts for first regency
        $firstReg = $regencies[0];
        $distRes = $this->getJson('/api/regions/online?type=districts&parent_id=' . $firstReg['id']);
        $distRes->assertStatus(200);
        $districts = $distRes->json('data');
        $this->assertNotEmpty($districts);

        // 4. Fetch Villages for first district
        $firstDist = $districts[0];
        $villRes = $this->getJson('/api/regions/online?type=villages&parent_id=' . $firstDist['id']);
        $villRes->assertStatus(200);
        $villages = $villRes->json('data');
        $this->assertNotEmpty($villages);
    }
}

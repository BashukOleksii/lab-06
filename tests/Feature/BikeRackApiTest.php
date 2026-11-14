<?php

use App\Models\District;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Support\BikeRackFixtures;

uses(RefreshDatabase::class);

test('Гість не може створити парковку для велосипедів', function (): void {
    $district = District::factory()->create();

    $this->postJson(route('api.bike-racks.store'), BikeRackFixtures::payload($district))
        ->assertUnauthorized();

    $this->assertDatabaseCount('bike_racks', 0);
});

test('Власник може переглянути список своїх парковок для велосипедів', function (): void {
    $scenario = BikeRackFixtures::filteredList();

    Sanctum::actingAs($scenario['owner']);

    $response = $this->getJson(route('api.bike-racks.index', $scenario['filters']))
        ->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('meta.total', 2);

    expect(array_column($response->json('data'), 'id'))
        ->toBe($scenario['expectedIds']);
});

test('Власник може створити нову парковку для велосипедів', function (): void {
    $owner = User::factory()->create();
    $district = District::factory()->create();
    $payload = BikeRackFixtures::payload($district);

    Sanctum::actingAs($owner);

    $response = $this->postJson(route('api.bike-racks.store'), $payload)
        ->assertCreated()
        ->assertJsonPath('data.title', $payload['title'])
        ->assertJsonPath('data.status', 'draft')
        ->assertJsonPath('data.district.id', $district->id);

    $rackId = $response->json('data.id');

    $response->assertHeader('Location', route('api.bike-racks.show', [
        'bike_rack' => $rackId,
    ]));

    $this->assertDatabaseHas('bike_racks', [
        'id' => $rackId,
        'owner_id' => $owner->id,
        'district_id' => $district->id,
        'title' => $payload['title'],
        'slot_count' => 5,
        'status' => 'draft',
    ]);
});

test('Власник не може створити парковку з неправильним числом слотів', function (): void {
    $owner = User::factory()->create();
    $district = District::factory()->create();
    $payload = [...BikeRackFixtures::payload($district), 'slot_count' => 0];

    Sanctum::actingAs($owner);

    $this->postJson(route('api.bike-racks.store'), $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['slot_count']);

    $this->assertDatabaseCount('bike_racks', 0);
});

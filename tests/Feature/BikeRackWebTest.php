<?php

use App\Models\BikeRack;
use App\Models\District;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\BikeRackFixtures;

uses(RefreshDatabase::class);

test('Гість не може створити парковку для велосипедів', function (): void {
    $district = District::factory()->create();

    $this->post(route('bike-racks.store'), BikeRackFixtures::payload($district))
        ->assertRedirectToRoute('login');

    $this->assertDatabaseCount('bike_racks', 0);
});

test('Список парковок для велосипедів відображається правильно', function (): void {
    $scenario = BikeRackFixtures::filteredList();

    $response = $this->actingAs($scenario['owner'], 'web')
        ->get(route('bike-racks.index', $scenario['filters']))
        ->assertOk()
        ->assertViewIs('bike-racks.index')
        ->assertSeeInOrder(['Бібліотека Б', 'Бібліотека А'])
        ->assertDontSee($scenario['foreignTitle']);

    $bikeRacks = $response->viewData('bikeRacks');

    expect($bikeRacks->total())->toBe(2);
    expect($bikeRacks->getCollection()->pluck('id')->all())
        ->toBe($scenario['expectedIds']);
});

test('Власник може створити нову парковку для велосипедів', function (): void {
    $owner = User::factory()->create();
    $district = District::factory()->create();
    $payload = BikeRackFixtures::payload($district);

    $response = $this->actingAs($owner, 'web')
        ->post(route('bike-racks.store'), $payload);

    $rack = BikeRack::query()->sole();

    $response->assertRedirectToRoute('bike-racks.show', [
        'bike_rack' => $rack,
    ]);

    $this->assertDatabaseHas('bike_racks', [
        'id' => $rack->id,
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

    $this->actingAs($owner, 'web')
        ->from(route('bike-racks.create'))
        ->post(route('bike-racks.store'), $payload)
        ->assertRedirectToRoute('bike-racks.create')
        ->assertSessionHasErrors('slot_count')
        ->assertSessionHasInput('title', $payload['title']);

    $this->assertDatabaseCount('bike_racks', 0);
});

<?php

namespace Database\Seeders;

use App\Models\BikeRack;
use App\Models\District;
use App\Models\User;
use Illuminate\Database\Seeder;

class BikeRackSeeder extends Seeder
{
    public function run(): void
    {
        $owners = [
            'first' => User::firstOrCreate(
                ['email' => 'owner1@example.test'],
                ['name' => 'Перший власник', 'password' => 'bike-parking-demo'],
            ),
            'second' => User::firstOrCreate(
                ['email' => 'owner2@example.test'],
                ['name' => 'Другий власник', 'password' => 'bike-parking-demo'],
            ),
        ];

        $districts = District::query()->get()->keyBy('code');

        $examples = [
            ['first', 'north', 'Біля бібліотеки', 4, 7, 'draft'],
            ['first', 'north', 'Біля школи', 5, 7, 'draft'],
            ['first', 'central', 'Біля театру', 9, 7, 'draft'],
            ['first', 'central', 'Біля міської ради', 10, 7, 'draft'],
            ['first', 'east', 'Біля стадіону', 8, -1, 'draft'],
            ['first', 'south', 'Біля парку', 7, 0, 'confirmed'],

            ['second', 'central', 'Біля площі', 12, 0, 'confirmed'],
            ['second', 'north', 'Біля амбулаторії', 7, 7, 'draft'],
            ['second', 'east', 'Біля ринку', 6, 0, 'confirmed'],
            ['second', 'south', 'Біля зупинки', 8, 7, 'draft'],
            ['second', 'east', 'Біля вокзалу', 11, 7, 'draft'],
            ['second', 'south', 'Біля спортзалу', 9, 0, 'confirmed'],
        ];

        $today = today('Europe/Kyiv')->toImmutable();

        foreach ($examples as [$ownerKey, $districtCode, $title, $slots, $days, $status]) {
            $owner = $owners[$ownerKey];

            if ($owner->bikeRacks()->where('title', $title)->exists()) {
                continue;
            }

            BikeRack::factory()
                ->for($owner, 'owner')
                ->for($districts[$districtCode], 'district')
                ->create([
                    'title' => $title,
                    'description' => null,
                    'slot_count' => $slots,
                    'scheduled_opening_on' => $today->addDays($days)->toDateString(),
                    'status' => $status,
                ]);
        }
    }
}

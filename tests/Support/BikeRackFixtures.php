<?php

namespace Tests\Support;

use App\Models\BikeRack;
use App\Models\District;
use App\Models\User;

class BikeRackFixtures
{
    public static function payload(District $district): array
    {
        return [
            'district_id' => $district->id,
            'title' => 'Парковка біля бібліотеки',
            'slot_count' => 5,
            'scheduled_opening_on' => today('Europe/Kyiv')->toDateString(),
        ];
    }

    public static function filteredList(): array
    {
        $owner = User::factory()->create();
        $otherOwner = User::factory()->create();
        $district = District::factory()->create();
        $otherDistrict = District::factory()->create();
        $date = today('Europe/Kyiv')->addDays(7)->toDateString();

        $matching = [
            'status' => 'draft',
            'scheduled_opening_on' => $date,
        ];

        $first = BikeRack::factory()->for($owner, 'owner')->for($district)
            ->create([...$matching, 'title' => 'Бібліотека А', 'description' => null]);

        $second = BikeRack::factory()->for($owner, 'owner')->for($district)
            ->create([...$matching, 'title' => 'Бібліотека Б', 'description' => null]);

        $foreign = BikeRack::factory()->for($otherOwner, 'owner')->for($district)
            ->create([
                ...$matching,
                'title' => 'Чужа парковка',
                'description' => 'Бібліотека',
            ]);

        BikeRack::factory()->for($owner, 'owner')->for($otherDistrict)
            ->create([...$matching, 'title' => 'Бібліотека в іншому кварталі']);

        BikeRack::factory()->for($owner, 'owner')->for($district)
            ->create([
                ...$matching,
                'title' => 'Бібліотека, відкриття підтверджено',
                'status' => 'confirmed',
            ]);

        BikeRack::factory()->for($owner, 'owner')->for($district)
            ->create([...$matching, 'title' => 'Біля школи', 'description' => null]);

        return [
            'owner' => $owner,
            'foreignTitle' => $foreign->title,
            'expectedIds' => [$second->id, $first->id],
            'filters' => [
                'district_id' => $district->id,
                'status' => 'draft',
                'search' => 'Бібліотека',
            ],
        ];
    }
}

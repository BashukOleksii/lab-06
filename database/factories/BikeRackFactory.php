<?php

namespace Database\Factories;

use App\Models\BikeRack;
use App\Models\District;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BikeRack>
 */
class BikeRackFactory extends Factory
{
    public function definition(): array
    {
        return [
            'owner_id' => User::factory(),
            'district_id' => District::factory(),
            'title' => 'Велопарковка ' . fake()->words(3, true),
            'description' => fake()->sentence(),
            'slot_count' => fake()->numberBetween(1, 20),
            'scheduled_opening_on' => today('Europe/Kyiv')
                ->addDays(7)
                ->toDateString(),
            'status' => 'draft',
        ];
    }
}

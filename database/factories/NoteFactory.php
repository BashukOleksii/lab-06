<?php

namespace Database\Factories;

use App\Models\BikeRack;
use App\Models\Note;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Note>
 */
class NoteFactory extends Factory
{
    public function definition(): array
    {
        return [
            'bike_rack_id' => BikeRack::factory(),

            'title' => fake()->sentence(4),

            'body' => fake()->paragraph(),
        ];
    }
}

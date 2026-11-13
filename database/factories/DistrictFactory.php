<?php

namespace Database\Factories;

use App\Models\District;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<District>
 */
class DistrictFactory extends Factory
{
    public function definition(): array
    {
        $code = fake()->unique()->bothify('test-####');

        return [
            'name' => 'Тестовий квартал ' . $code,
            'code' => $code,
        ];
    }
}

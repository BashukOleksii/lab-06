<?php

namespace Database\Seeders;

use App\Models\District;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    public function run(): void
    {
        $districts = [
            'central' => 'Центральний квартал',
            'north' => 'Північний квартал',
            'east' => 'Східний квартал',
            'south' => 'Південний квартал',
        ];

        foreach ($districts as $code => $name) {
            District::firstOrCreate(
                ['code' => $code],
                ['name' => $name],
            );
        }
    }
}

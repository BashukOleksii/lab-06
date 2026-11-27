<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class NoteSeeder extends Seeder
{
    public function run(): void
    {
        $owners = User::query()
            ->whereIn('email', [
                'owner1@example.test',
                'owner2@example.test',
            ])
            ->get();

        foreach ($owners as $owner) {
            $bikeRacks = $owner->bikeRacks()
                ->orderBy('id')
                ->limit(3)
                ->get();

            foreach ($bikeRacks as $bikeRack) {
                $bikeRack->notes()->firstOrCreate(
                    [
                        'title' => 'Технічне обслуговування',
                    ],
                    [
                        'body' => 'Необхідно перевірити стан '
                            . 'велосипедних стійок та кріплень.',
                    ],
                );

                $bikeRack->notes()->firstOrCreate(
                    [
                        'title' => 'Освітлення території',
                    ],
                    [
                        'body' => 'Розглянути можливість '
                            . 'встановлення додаткового освітлення.',
                    ],
                );
            }
        }
    }
}

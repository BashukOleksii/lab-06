<?php

namespace App\Services;

use App\Exceptions\BikeRackOpeningException;
use App\Models\BikeRack;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Events\BikeRackOpened;

class BikeRackWriter
{
    private const VARIANT_NUMBER = 3;

    private const MIN_SLOTS = 2 + (self::VARIANT_NUMBER % 6);

    private const CENTRAL_MIN_SLOTS = 10;

    public function create(User $user, array $data): BikeRack
    {
        Gate::forUser($user)->authorize('create', BikeRack::class);

        $bikeRack = $user->bikeRacks()->make($data);
        $bikeRack->status = 'draft';
        $bikeRack->save();

        return $bikeRack;
    }

    public function update(User $user, BikeRack $bikeRack, array $data): BikeRack
    {
        Gate::forUser($user)->authorize('update', $bikeRack);

        $bikeRack->fill($data);
        $bikeRack->save();

        return $bikeRack;
    }

    public function delete(User $user, BikeRack $bikeRack): void
    {
        Gate::forUser($user)->authorize('delete', $bikeRack);

        $bikeRack->delete();
    }

    public function open(User $user, BikeRack $bikeRack): BikeRack
    {
        return DB::transaction(function () use ($user, $bikeRack): BikeRack {
            $current = BikeRack::query()
                ->lockForUpdate()
                ->findOrFail($bikeRack->getKey());

            Gate::forUser($user)->authorize('open', $current);

            if ($current->status !== 'draft') {
                throw new BikeRackOpeningException(
                    'Відкриття цієї велопарковки вже підтверджено.'
                );
            }

            if ($current->scheduled_opening_on->toDateString() < today('Europe/Kyiv')->toDateString()) {
                throw new BikeRackOpeningException(
                    'Запланована дата відкриття вже минула.'
                );
            }

            $minimum = self::MIN_SLOTS;

            if ($current->district->code === 'central') {
                $minimum = max($minimum, self::CENTRAL_MIN_SLOTS);
            }

            if ($current->slot_count < $minimum) {
                throw new BikeRackOpeningException(
                    "Для відкриття в цьому кварталі потрібно щонайменше {$minimum} місць."
                );
            }

            $current->status = 'confirmed';
            $current->save();


            BikeRackOpened::dispatch($current->id);

            return $current;
        });
    }
}

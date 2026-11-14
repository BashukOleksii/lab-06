<?php

namespace App\Listeners;

use App\Events\BikeRackOpened;
use App\Models\BikeRack;
use App\Models\ConfirmationReceipt;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class CreateConfirmationReceipt implements ShouldQueue
{
    public function viaQueue(): string
    {
        return 'receipts';
    }

    public function handle(BikeRackOpened $event): void
    {
        DB::transaction(function () use ($event): void {
            $bikeRack = BikeRack::query()
                ->lockForUpdate()
                ->find($event->bikeRackId);

            if ($bikeRack === null || $bikeRack->status !== 'confirmed') {
                return;
            }

            ConfirmationReceipt::firstOrCreate(
                ['bike_rack_id' => $bikeRack->id],
                ['summary' => "Відкриття велопарковки №{$bikeRack->id} підтверджено."],
            );
        });
    }
}

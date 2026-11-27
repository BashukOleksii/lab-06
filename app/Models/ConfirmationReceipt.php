<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConfirmationReceipt extends Model
{
    protected $fillable = [
        'bike_rack_id',
        'summary',
    ];

    public function bikeRack(): BelongsTo
    {
        return $this->belongsTo(BikeRack::class, 'bike_rack_id');
    }
}

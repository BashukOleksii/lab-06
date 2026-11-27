<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BikeRack extends Model
{
    use HasFactory;

    protected $fillable = [
        'district_id',
        'title',
        'description',
        'slot_count',
        'scheduled_opening_on',
    ];

    protected function casts(): array
    {
        return [
            'slot_count' => 'integer',
            'scheduled_opening_on' => 'immutable_date',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(
            District::class,
            'district_id'
        );
    }

    public function confirmationReceipt(): HasOne
    {
        return $this->hasOne(
            ConfirmationReceipt::class,
            'bike_rack_id'
        );
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class, 'bike_rack_id')
            ->orderByDesc('created_at')
            ->orderByDesc('id');
    }
}

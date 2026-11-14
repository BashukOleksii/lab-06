<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfirmationReceipt extends Model
{
    protected $fillable = [
        'bike_rack_id',
        'summary',
    ];
}

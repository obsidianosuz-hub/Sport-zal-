<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreadmillSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'nfc_device_id',
        'started_at',
        'ended_at',
        'duration_minutes',
        'energy_kcal',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function device()
    {
        return $this->belongsTo(NfcDevice::class, 'nfc_device_id');
    }
}

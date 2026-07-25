<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NfcDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'mac_address',
        'ip_address',
        'device_type',
        'status',
        'last_ping_at',
    ];

    protected $casts = [
        'last_ping_at' => 'datetime',
    ];

    public function sessions()
    {
        return $this->hasMany(TreadmillSession::class);
    }
}

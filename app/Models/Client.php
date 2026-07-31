<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['name', 'phone', 'subscription_type', 'subscription_expires_at', 'total_paid', 'trainer_id'];

    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }
}

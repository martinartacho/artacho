<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FcmToken extends Model
{
    protected $fillable = [
        'user_id',
        'token',
        'device_type',
        'device_name',
        'last_used_at',
        'is_valid',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

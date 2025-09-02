<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventQuestion extends Model
{
    protected $fillable = [
        'event_id', 'question', 'type', 'options', 'required'
    ];

    protected $casts = [
        'options' => 'array',
        'required' => 'boolean'
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(EventAnswer::class);
    }
}
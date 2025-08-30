<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $fillable = [
        'title', 'description', 'start', 'end', 'color', 
        'max_users', 'visible', 'start_visible', 'end_visible', 'event_type_id'
    ];
    
    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
        'start_visible' => 'datetime',
        'end_visible' => 'datetime',
        'visible' => 'boolean',
    ];
    
    public function eventType(): BelongsTo
    {
        return $this->belongsTo(EventType::class);
    }
    
    public function questions(): HasMany
    {
        return $this->hasMany(EventQuestion::class);
    }
}
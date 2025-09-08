<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $fillable = [
        'title', 'description', 'start', 'end', 'color', 
        'max_users', 'visible', 'start_visible', 'end_visible', 'event_type_id',
        'recurrence_type', 'recurrence_interval', 'recurrence_end_date', 'recurrence_count'
    ];
    
    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
        'start_visible' => 'datetime',
        'end_visible' => 'datetime',
        'visible' => 'boolean',
        'recurrence_end_date' => 'date',
    ];
    
    public function eventType(): BelongsTo
    {
        return $this->belongsTo(EventType::class);
    }
    
    public function questions(): HasMany
    {
        return $this->hasMany(EventQuestion::class);
    }

    // Accessor para facilitar el acceso a la información de recurrencia
    public function getRecurrenceInfoAttribute()
    {
        if ($this->recurrence_type === 'none') {
            return null;
        }
        
        $types = [
            'daily' => __('days'),
            'weekly' => __('weeks'),
            'monthly' => __('months'),
            'yearly' => __('years'),
        ];
        
        return [
            'type' => $this->recurrence_type,
            'interval' => $this->recurrence_interval,
            'type_text' => __(ucfirst($this->recurrence_type)),
            'interval_text' => $types[$this->recurrence_type] ?? '',
        ];
    }

    
}
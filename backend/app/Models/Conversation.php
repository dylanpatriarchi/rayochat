<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Conversation extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'company_id',
        'conversation_id',
        'question',
        'answer',
        'sources',
        'rating',
        'rated_at',
        'response_time_ms',
    ];

    protected $casts = [
        'rated_at' => 'datetime',
    ];

    /**
     * Boot method to automatically generate UUID
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($conversation) {
            if (empty($conversation->id)) {
                $conversation->id = (string) Str::uuid();
            }
            if (empty($conversation->conversation_id)) {
                $conversation->conversation_id = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the company that owns this conversation
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get sources as array
     */
    public function getSourcesArrayAttribute(): array
    {
        return $this->sources ? explode(',', $this->sources) : [];
    }

    /**
     * Check if conversation is rated
     */
    public function isRated(): bool
    {
        return $this->rating !== null;
    }
}

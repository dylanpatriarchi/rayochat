<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Site extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'url',
        'api_key',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($site) {
            if (empty($site->api_key)) {
                $site->api_key = 'rc_s_' . Str::random(32);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Analytics extends Model
{
    protected $fillable = [
        'site_id',
        'message',
        'category',
        'confidence',
        'classification_data',
    ];

    protected $casts = [
        'classification_data' => 'array',
        'confidence' => 'decimal:4',
    ];

    /**
     * Relationship with Site model
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }
}

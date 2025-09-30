<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'filename',
        'file_path',
        'file_type',
        'file_size',
        'content',
        'metadata',
        'status',
        'processed_at',
        'error_message',
    ];

    protected $casts = [
        'metadata' => 'array',
        'processed_at' => 'datetime',
    ];

    /**
     * Get the company that owns this document
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Check if document is processed
     */
    public function isProcessed(): bool
    {
        return $this->status === 'processed';
    }

    /**
     * Check if document is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Get file size in human readable format
     */
    public function getFileSizeHumanAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'hash',
        'api_key',
        'description',
        'website',
        'email',
        'phone',
        'business_info',
        'is_active',
    ];

    protected $hidden = [
        'api_key',
    ];

    protected $casts = [
        'business_info' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Boot method to automatically generate hash and API key
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($company) {
            if (empty($company->hash)) {
                $company->hash = hash('sha256', uniqid($company->name, true));
            }
            if (empty($company->api_key)) {
                $company->api_key = 'sk_' . Str::random(48);
            }
        });
    }

    /**
     * Get the user that owns this company
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all documents for this company
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Get all conversations for this company
     */
    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }

    /**
     * Get all change requests for this company
     */
    public function changeRequests()
    {
        return $this->hasMany(ChangeRequest::class);
    }

    /**
     * Get the RAG endpoint URL for this company
     */
    public function getRagEndpoint(): string
    {
        return config('services.rag.url') . '/ask/' . $this->hash;
    }
}

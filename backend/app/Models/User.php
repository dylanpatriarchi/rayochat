<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;

class User extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'email_hash',
        'role',
        'is_active',
        'email_verified_at',
    ];

    protected $hidden = [
        'email_hash',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Boot method to automatically generate email hash
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->email_hash)) {
                $user->email_hash = hash('sha256', $user->email);
            }
        });
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is site owner
     */
    public function isSiteOwner(): bool
    {
        return $this->role === 'site_owner';
    }

    /**
     * Get the company associated with this user
     */
    public function company()
    {
        return $this->hasOne(Company::class);
    }

    /**
     * Get change requests made by this admin
     */
    public function changeRequests()
    {
        return $this->hasMany(ChangeRequest::class, 'requested_by_admin_id');
    }
}

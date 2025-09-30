<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChangeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'requested_by_admin_id',
        'description',
        'proposed_changes',
        'status',
        'rejection_reason',
        'approved_at',
        'rejected_at',
    ];

    protected $casts = [
        'proposed_changes' => 'array',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    /**
     * Get the company for this change request
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the admin who requested the change
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'requested_by_admin_id');
    }

    /**
     * Check if request is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if request is approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if request is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Approve the change request
     */
    public function approve()
    {
        $this->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);
    }

    /**
     * Reject the change request
     */
    public function reject(string $reason = null)
    {
        $this->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejection_reason' => $reason,
        ]);
    }
}

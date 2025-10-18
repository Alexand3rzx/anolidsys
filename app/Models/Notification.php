<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'message',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    /**
     * Relationship: Notification belongs to a User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for admin notifications — show only purok-wide messages.
     */
    public function scopeForAdmin($query)
    {
        return $query->where(function ($q) {
            $q->where('message', 'like', '%requested medicine%')
              ->orWhere('message', 'like', '%updated a pending request%');
        });
    }

    /**
     * Scope for useradmin notifications — show only personal medicine updates.
     */
    public function scopeForUseradmin($query)
    {
        return $query->where(function ($q) {
            $q->where('message', 'like', '%Your request%')
              ->orWhere('message', 'like', '%Your medicine request%');
        });
    }

    /**
     * Helper: Short preview for notification dropdown.
     */
    public function getPreviewAttribute()
    {
        return Str::limit($this->message, 80);
    }
}

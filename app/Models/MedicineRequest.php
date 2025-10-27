<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineRequest extends Model
{
    use HasFactory;

   protected $fillable = [
    'user_id',
    'medicine_id',
    'batch_id',
    'quantity',
    'status',
    'pickup_code',
    'pickup_date',
];
    protected $dates = [
        'pickup_date',
        'completed_at',
        'created_at',
        'updated_at',
    ];

    // Relationships
    public function useradmin()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    // Optional: Helper methods for status checks
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isAwaitingPickup()
    {
        return $this->status === 'awaiting_pickup';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineTransaction extends Model
{
    use HasFactory;

    // Define the table name (optional if it's the default plural form)
    protected $table = 'medicine_transactions';

    // Define which fields are mass assignable
    protected $fillable = [
        'medicine_id',
        'quantity',
        'donor',
        'receiver',
        'details',
        'type',
    ];

    // Define the relationship with the Medicine model
    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}

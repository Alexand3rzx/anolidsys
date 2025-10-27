<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineBatch extends Model
{
    use HasFactory;

    protected $fillable = ['batch_number', 'stock', 'expiration'];

    // Many-to-many relationship with medicines
    public function medicines()
    {
        return $this->belongsToMany(
            Medicine::class,
            'medicine_batch_medicine',   // pivot table name
            'batch_id',                  // foreign key for this model
            'medicine_id'                // foreign key for the related model
        )->withTimestamps();
    }
    public function transactions()
    {
        return $this->hasMany(MedicineTransaction::class);
    }
}
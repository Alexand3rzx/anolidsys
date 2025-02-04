<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',  // 'Pregnant' or 'Infant'
        'birthday',
        'age',
        'months_pregnant', // Nullable (only for Pregnant)
        'due_date',        // Nullable (only for Pregnant)
        'weight',          // Nullable (only for Infant)
        'height',          // Nullable (only for Infant)
    ];

    protected $casts = [
        'birthday' => 'date',
        'due_date' => 'date',
    ];
}

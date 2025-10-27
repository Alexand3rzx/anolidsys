<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompletedImmunizationRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'pregnant_id',
        'records',
    ];
}
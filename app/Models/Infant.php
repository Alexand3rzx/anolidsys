<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Infant extends Model
{
    use HasFactory;

    protected $fillable = [
        'child_name',
        'child_bday',
        'child_place',
        'child_address',
        'child_mother',
        'child_father',
        'child_gender',
        'child_height',
        'child_weight',
    ];
}

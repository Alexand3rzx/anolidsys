<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pregnant extends Model
{
    use HasFactory;

    protected $table = 'pregnants'; // Ensure the table name matches

    protected $fillable = [
        'prgname', 'prgage', 'prgbday', 'prgaddress', 'prgoccupation',
        'prgreligion', 'prgmother_name', 'partner_name', 'partner_age',
        'partner_bday', 'partner_occupation', 'partner_religion', 'partner_number'
    ];
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model
{
    use HasFactory;

    protected $fillable = [
        'Fname', 'Lname', 'category', 'birthday', 'age', 
        'months_pregnant', 'due_date', 'weight', 'height',
        'address', 'occupation', 'educational_attainment', 'contact_number',
        'religion', 'mothers_name', 'partner_name', 
        'partner_age', 'partner_bday', 'partner_occupation', 
        'partner_eduattain', 'partner_religion'
    ];
    

    protected $casts = [
        'birthday' => 'date',
        'due_date' => 'date',
        'partner_bday' => 'date'
    ];

    // Helper method to get full name
    public function getFullNameAttribute()
    {
        return "{$this->Fname} {$this->Lname}";
    }
}



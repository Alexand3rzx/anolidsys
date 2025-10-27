<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pregnant extends Model
{
    use HasFactory;

    protected $table = 'pregnants'; // Ensure the table name matches

    protected $fillable = [
        'prgname', 'prgage', 'prgbday', 'prgaddress', 'prgtimes','prgoccupation',
        'prgreligion', 'prgmother_name', 'partner_name', 'partner_age',
        'partner_bday', 'partner_occupation', 'partner_religion', 'partner_number','purok','photo'
    ];

    public function firstPregnancyRecords()
{
    return $this->hasMany(FirstPregnancyImmunization::class, 'pregnant_id');
}

public function secondToFifthPregnancyRecords()
{
    return $this->hasMany(SecondToFifthPregnancyImmunization::class, 'pregnant_id');
}

public function sixthPregnancyRecords()
{
    return $this->hasMany(SixthPregnancyImmunization::class, 'pregnant_id');
}

public function completedImmunizationRecords()
{
    return $this->hasMany(\App\Models\CompletedImmunizationRecord::class);
}
}


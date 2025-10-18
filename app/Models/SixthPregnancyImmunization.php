<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SixthPregnancyImmunization extends Model
{
    use HasFactory;

    protected $table = 'sixth_pregnancy_immunizations';

    protected $fillable = [
        'pregnant_id',
        'visit_number',
        'visit_date',
        'expected_month',
        'vaccine_given',
        'remarks',
    ];

    public function pregnant()
    {
        return $this->belongsTo(Pregnant::class, 'pregnant_id');
    }
}

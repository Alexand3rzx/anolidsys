<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Immunization extends Model
{
    protected $fillable = [
        'infant_id', 'bcg_date', 'hepatitis_b_date',
        'pentavalent_date_1', 'pentavalent_date_2', 'pentavalent_date_3',
        'opv_date_1', 'opv_date_2', 'opv_date_3',
        'ipv_date_1', 'ipv_date_2',
        'pcv_date_1', 'pcv_date_2', 'pcv_date_3',
        'mmr_date_1', 'mmr_date_2'
    ];

    public function infant()
    {
        return $this->belongsTo(Infant::class);
    }
}

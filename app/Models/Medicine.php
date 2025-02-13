<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        'name', 'details', 'stock','expiration'
    ];

    public function transactions()
{
    return $this->hasMany(MedicineTransaction::class);
}
}

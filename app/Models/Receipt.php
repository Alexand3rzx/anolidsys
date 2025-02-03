<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $fillable = ['medicine_id', 'donated_by', 'received_by', 'quantity', 'details'];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}

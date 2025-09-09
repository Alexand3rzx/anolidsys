<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'medicine_id',
        'user_id',
        'quantity',
        'status',
    ];

   public function useradmin()
{
    return $this->belongsTo(User::class, 'user_id');
}

public function medicine()
{
    return $this->belongsTo(Medicine::class);
}

    
}

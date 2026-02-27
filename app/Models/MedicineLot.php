<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineLot extends Model
{
    use HasFactory;

    protected $fillable = [
        'medicine_id',
        'lot_number',
        'mfd_date',
        'exp_date',
        'stock',
        'cost_price',
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}

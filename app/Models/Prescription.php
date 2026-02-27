<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'care_instruction_id',
        'medicine_id',
        'amount',
        'dosage',
        'dispensed',
    ];

    public function careInstruction()
    {
        return $this->belongsTo(CareInstruction::class, 'care_instruction_id', 'ID_CI');
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'stock',
        'price',
    ];

    public function lots()
    {
        return $this->hasMany(MedicineLot::class);
    }
}

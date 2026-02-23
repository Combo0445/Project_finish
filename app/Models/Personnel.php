<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personnel extends Model
{
    use HasFactory;

    protected $table = 'personnels';
    protected $primaryKey = 'ID_Personnel';
    protected $fillable = [
        'Type_Personnel'
    ];
    public $timestamps = false;
}

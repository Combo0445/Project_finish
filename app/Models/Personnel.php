<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personnel extends Model
{
    use HasFactory;

    protected $table = 'personnels';
    protected $primaryKey = 'ID_Personnel';
    public $timestamps = false; // The migration doesn't have timestamps

    protected $fillable = [
        'ID_Personnel',
        'Type_Personnel',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'ID_Personnel', 'ID_Personnel');
    }
}

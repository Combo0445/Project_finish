<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddressElderly extends Model
{
    use HasFactory;

    protected $table = 'address_elderlys';
    protected $primaryKey = 'ID_Address';
    protected $fillable = [
        'ID_Elderly', 'Name_Elderly', 'Latitude_position', 'Longitude_position'
    ];

    public $timestamps = false;

    public function elderly()
    {
        return $this->belongsTo(Elderly::class, 'ID_Elderly', 'ID_Elderly');
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Elderly extends Model
{
    use HasFactory;

    protected $table = 'elderlys';
    protected $primaryKey = 'ID_Elderly';

    protected $fillable = [
        'Name_Elderly',
        'Birthday',
        'Address',
        'Phone_Elderly',
        'Image_Elderly'
    ];

    public $timestamps = false;

    public function barthel_adl()
    {
        return $this->hasOne(BarthelAdl::class, 'ID_Elderly', 'ID_Elderly')->latestOfMany();
    }

    public function barthel_adls()
    {
        return $this->hasMany(BarthelAdl::class, 'ID_Elderly', 'ID_Elderly');
    }

    public function care_giver()
    {
        return $this->hasOne(CareGiver::class, 'ID_Elderly', 'ID_Elderly')->latestOfMany();
    }

    public function addressElderly()
    {
        return $this->hasOne(AddressElderly::class, 'ID_Elderly', 'ID_Elderly');
    }
}

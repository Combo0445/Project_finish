<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceReport extends Model
{
    use HasFactory;
    protected $table = 'performance_report';
    protected $primaryKey = 'id';
    protected $fillable = [
        'ID_Elderly',
        'ID_ADL',
        'ID_TAI',
        'ID_CG',
        'ID_User',
        'Date',
        'State',
        'Activity',
        'Problems',
        'Relative',
        'Note',
    ];
    public $timestamps = true;

    public function elderly()
    {
        // foreignKey = คอลัมน์ใน performance_report,
        // ownerKey  = primaryKey ของ Elderly
        return $this->belongsTo(Elderly::class, 'ID_Elderly', 'ID_Elderly');
    }

    public function caregiver()
    {
        return $this->belongsTo(CareGiver::class, 'ID_CG', 'ID_CG');
    }

    public function adl()
    {
        // สมมติว่าคอลัมน์จริงใน DB ชื่อ 'ID_ADL'
        return $this->belongsTo(BarthelAdl::class, 'ID_ADL', 'ID_ADL');
    }

    public function tai()
    {
        // เมื่อเราแก้ ScoreTAI::$primaryKey = 'ID_TAI' 
        return $this->belongsTo(ScoreTAI::class, 'ID_TAI', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'ID_User', 'ID_User');
    }

}

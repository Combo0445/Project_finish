<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoreTAI extends Model
{
    use HasFactory;

    protected $table = 'score_t_a_i_s';

    protected $fillable = [
        'ID_Elderly',
        'ID_ADL',
        'ID_User',
        'mobility',
        'confuse',
        'feed',
        'toilet',
        'group',
    ];

    public function elderly()
    {
        return $this->belongsTo(Elderly::class, 'ID_Elderly', 'ID_Elderly');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'ID_User', 'ID_User');
    }

    public function barthelAdl()
    {
        return $this->belongsTo(BarthelAdl::class, 'ID_ADL', 'ID_ADL');
    }
}

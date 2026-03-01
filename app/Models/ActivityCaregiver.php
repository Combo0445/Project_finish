<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;

class ActivityCaregiver extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'activity_caregivers';
    protected $primaryKey = 'ID_ACG';
    protected $fillable = [
        'ID_CG',
        'Date_ACG',
        'Evaluate',
        'Dress_the_wound',
        'Rehabilitate',
        'Clean_body',
        'Take_care_medicine',
        'Take_care_feeding',
        'Environmental',
        'Take_exercise',
        'Give_advice_consult',
        'Take_to_see_a_doctor',
        'Other',
        'Take_to_make_merit',
        'Take_to_market',
        'Take_to_meet_friends',
        'Take_to_allowance',
        'Talk_as_friends',
        'Other_specified',
        'Problem',
        'Solution'
    ];

    public $timestamps = false;

    public function caregiver()
    {
        return $this->belongsTo(CareGiver::class, 'ID_CG', 'ID_CG');
    }
}

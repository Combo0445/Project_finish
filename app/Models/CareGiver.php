<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;

class CareGiver extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'care_givers';
    protected $primaryKey = 'ID_CG';
    protected $fillable = [
        'ID_ADL',
        'Related',
        'Phone_CG',
        'ID_Elderly',
        'Name_CG',
        'Name_Elderly',
        'Birthday',
        'Weight',
        'Height',
        'Waist',
        'Address',
        'Group_ADL',
        'Disease',
        'Disability',
        'Rights',
        'Date_CG',
        'Consciousness',
        'Vital_signs',
        'Bedsores',
        'Pain',
        'Swelling',
        'Itchy_rash',
        'Stiff_joints',
        'Malnutrition',
        'Eating',
        'Swallowing',
        'Defecation',
        'Urinary_excretion',
        'Taking_medicine',
        'Emotional_state',
        'Economic_problems',
        'Social_problems',
        'Doctor_FU',
        'Other_problems',
        'Assistance',
        'Reporter',
        'Picture',
    ];

    public $timestamps = false;

    public function activities()
    {
        return $this->hasMany(ActivityCaregiver::class, 'ID_CG', 'ID_CG');
    }
    public function elderly()
    {
        return $this->belongsTo(Elderly::class, 'ID_Elderly', 'ID_Elderly');
    }

    /**
     * Get the caregiver's image URL.
     */
    public function getImageUrlAttribute()
    {
        if ($this->Picture && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->Picture)) {
            return asset('storage/' . $this->Picture);
        }

        return asset('images/avatar_other.svg');
    }
}

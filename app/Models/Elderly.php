<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;

class Elderly extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'elderlys';
    protected $primaryKey = 'ID_Elderly';

    protected $fillable = [
        'Name_Elderly',
        'Gender',
        'Birthday',
        'Address',
        'Phone_Elderly',
        'Image_Elderly'
    ];

    /**
     * Boot the model to handle cascading soft deletes.
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($elderly) {
            // Cascade delete on related assessments
            $elderly->barthel_adl()->delete();
            $elderly->care_giver()->delete();
            $elderly->score_tai()->delete();
            $elderly->care_instructions()->delete();
            $elderly->performance_reports()->delete();
        });
    }

    // Return a full URL for the image or a gender-based avatar when missing
    public function getImageUrlAttribute()
    {
        if ($this->Image_Elderly && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->Image_Elderly)) {
            return asset('storage/' . $this->Image_Elderly);
        }

        if ($this->Image_Elderly && file_exists(public_path($this->Image_Elderly))) {
            return asset($this->Image_Elderly);
        }

        // Choose avatar based on Gender
        $gender = $this->Gender ?? '';
        if ($gender === 'ชาย') {
            return asset('images/avatar_male.svg');
        }
        if ($gender === 'หญิง') {
            return asset('images/avatar_female.svg');
        }

        return asset('images/avatar_other.svg');
    }

    public function barthel_adl()
    {
        return $this->hasOne(BarthelAdl::class, 'ID_Elderly', 'ID_Elderly');
    }

    public function care_giver()
    {
        return $this->hasOne(CareGiver::class, 'ID_Elderly', 'ID_Elderly');
    }

    public function addressElderly()
    {
        return $this->hasOne(AddressElderly::class, 'ID_Elderly', 'ID_Elderly');
    }

    public function score_tai()
    {
        return $this->hasOne(ScoreTAI::class, 'ID_Elderly', 'ID_Elderly');
    }

    // History Relations
    public function adl_history()
    {
        return $this->hasMany(BarthelAdl::class, 'ID_Elderly', 'ID_Elderly')->orderBy('created_at', 'desc');
    }

    public function cg_history()
    {
        return $this->hasMany(CareGiver::class, 'ID_Elderly', 'ID_Elderly')->orderBy('Date_CG', 'desc');
    }

    public function tai_history()
    {
        return $this->hasMany(ScoreTAI::class, 'ID_Elderly', 'ID_Elderly')->orderBy('created_at', 'desc');
    }

    public function care_instructions()
    {
        return $this->hasMany(CareInstruction::class, 'ID_Elderly', 'ID_Elderly');
    }

    public function performance_reports()
    {
        return $this->hasMany(PerformanceReport::class, 'ID_Elderly', 'ID_Elderly');
    }
}

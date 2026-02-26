<?php

namespace App\Observers;

use App\Models\BarthelAdl;
use App\Models\ScoreTAI;

class BarthelAdlObserver
{
    /**
     * Handle the BarthelAdl "created" event.
     */
    public function created(BarthelAdl $adl): void
    {
        $this->ensureScoreTAIExists($adl);
    }

    /**
     * Handle the BarthelAdl "updated" event.
     */
    public function updated(BarthelAdl $adl): void
    {
        $this->ensureScoreTAIExists($adl);
    }

    /**
     * Ensure a ScoreTAI record exists for the given ADL record if the score is in range.
     */
    private function ensureScoreTAIExists(BarthelAdl $adl): void
    {
        // If ADL score falls into TAI-trigger range, ensure ScoreTAI exists
        if (is_numeric($adl->Score_ADL) && $adl->Score_ADL >= 0 && $adl->Score_ADL <= 11) {
            $exists = ScoreTAI::where('ID_ADL', $adl->ID_ADL)->exists();
            if (!$exists) {
                ScoreTAI::create([
                    'ID_Elderly' => $adl->ID_Elderly,
                    'ID_ADL' => $adl->ID_ADL,
                    'ID_User' => $adl->ID_User,
                    'mobility' => null,
                    'confuse' => null,
                    'feed' => null,
                    'toilet' => null,
                    'group' => null,
                ]);
            }
        }
    }
}

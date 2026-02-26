<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActivityCaregiverRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'ID_Elderly' => $this->has('ID_Elderly') ? 'required|exists:elderlys,ID_Elderly' : 'nullable',
            'activity_date' => 'required|date',
            'evaluate' => 'nullable|string',
            'dress_the_wound' => 'nullable|string',
            'rehabilitate' => 'nullable|string',
            'clean_body' => 'nullable|string',
            'take_care_medicine' => 'nullable|string',
            'take_care_feeding' => 'nullable|string',
            'environmental' => 'nullable|string',
            'take_exercise' => 'nullable|string',
            'give_advice_consult' => 'nullable|string',
            'take_to_see_a_doctor' => 'nullable|string',
            'other_specified' => 'nullable|string',
            'take_to_make_merit' => 'nullable|string',
            'take_to_market' => 'nullable|string',
            'take_to_meet_friends' => 'nullable|string',
            'take_to_allowance' => 'nullable|string',
            'talk_as_friends' => 'nullable|string',
            'other_social_specified' => 'nullable|string',
            'problem' => 'nullable|string',
            'solution' => 'nullable|string',
        ];
    }
}

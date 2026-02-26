<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CareGiverRequest extends FormRequest
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
        $isPost = $this->isMethod('POST');

        return [
            'Name_CG' => 'required|string',
            'Related' => 'required|string',
            'Phone_CG' => 'required|string',
            'ID_Elderly' => $isPost ? 'required|exists:barthel_adls,ID_ADL' : 'required|string',
            'Name_Elderly' => 'required|string',
            'Address' => 'required|string',
            'Weight' => 'required|numeric',
            'Height' => 'required|numeric',
            'Waist' => 'required|numeric',
            'Group_ADL' => 'required|string',
            'Disease' => 'nullable|string',
            'Disability' => 'nullable|string',
            'Rights' => 'nullable|string',
            'Date' => $this->has('Date') ? 'required|date' : 'nullable|date',
            'Date_CG' => $this->has('Date_CG') ? 'required|date' : 'nullable|date',
            'Consciousness' => 'required|string',
            'Vital_signs' => 'required|string',
            'Bedsores' => 'required|string',
            'Bedsores_details' => 'nullable|string',
            'Pain' => 'required|string',
            'Pain_details' => 'nullable|string',
            'Swelling' => 'required|string',
            'Swelling_details' => 'nullable|string',
            'Itchy_rash' => 'required|string',
            'Itchy_rash_details' => 'nullable|string',
            'Stiff_joints' => 'required|string',
            'Stiff_joints_details' => 'nullable|string',
            'Malnutrition' => 'required|string',
            'Malnutrition_details' => 'nullable|string',
            'Eating' => 'required|string',
            'Swallowing' => 'required|string',
            'Defecation' => 'required|string',
            'Urinary_excretion' => 'required|string',
            'Taking_medicine' => 'required|string',
            'Emotional_state' => 'required|string',
            'Economic_problems' => 'required|string',
            'Economic_problems_details' => 'nullable|string',
            'Social_problems' => 'required|string',
            'Social_problems_details' => 'nullable|string',
            'Doctor_FU' => 'required|string',
            'Doctor_FU_details' => 'nullable|string',
            'Other_problems' => 'nullable|string',
            'Assistance' => 'nullable|string',
            'Reporter' => 'required|string',
            'Picture' => 'nullable|array|max:4',
            'Picture.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}

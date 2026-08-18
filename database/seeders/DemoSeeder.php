<?php

namespace Database\Seeders;

use App\Models\ActivityCaregiver;
use App\Models\BarthelAdl;
use App\Models\CareGiver;
use App\Models\CareInstruction;
use App\Models\Elderly;
use App\Models\ScoreTAI;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

/**
 * Seeds a portfolio-safe demo dataset: fictional elderly records and
 * ready-to-use Staff/Doctor accounts. Never contains real personal data.
 */
class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // Wipe any previously entered data so the demo never carries real PII
        Schema::disableForeignKeyConstraints();
        CareInstruction::truncate();
        ActivityCaregiver::truncate();
        ScoreTAI::truncate();
        CareGiver::truncate();
        BarthelAdl::truncate();
        Elderly::truncate();
        Schema::enableForeignKeyConstraints();

        $staff = User::updateOrCreate(
            ['Username' => 'demo_staff'],
            [
                'Email' => 'demo_staff@example.com',
                'Password' => Hash::make('Demo@2026'),
                'ID_Personnel' => 2,
                'Type_Personnel' => 'Staff',
                'Name_User' => 'เจ้าหน้าที่ (Demo)',
                'Address' => '-',
                'Phone' => '-',
                'Image_User' => 'images-user/Staff.png',
            ]
        );

        $doctor = User::updateOrCreate(
            ['Username' => 'demo_doctor'],
            [
                'Email' => 'demo_doctor@example.com',
                'Password' => Hash::make('Demo@2026'),
                'ID_Personnel' => 3,
                'Type_Personnel' => 'Doctor',
                'Type_Doctor' => 'ติดบ้าน',
                'Name_User' => 'แพทย์ (Demo)',
                'Address' => '-',
                'Phone' => '-',
                'Image_User' => 'images-user/Doctor.png',
            ]
        );

        $elderlyProfiles = [
            [
                'Name_Elderly' => 'สมชาย ใจดี',
                'Gender' => 'ชาย',
                'Birthday' => '1948-03-10',
                'Address' => '99 หมู่ 1 ตำบลตัวอย่าง อำเภอตัวอย่าง จังหวัดตัวอย่าง 10000',
                'Phone_Elderly' => '0800000001',
                'adl_scores' => [2, 1, 3, 2, 3, 2, 2, 1, 2, 2], // sum 20 -> ติดสังคม
            ],
            [
                'Name_Elderly' => 'สมหญิง รักษ์ดี',
                'Gender' => 'หญิง',
                'Birthday' => '1953-07-22',
                'Address' => '15 หมู่ 2 ตำบลตัวอย่าง อำเภอตัวอย่าง จังหวัดตัวอย่าง 10000',
                'Phone_Elderly' => '0800000002',
                'adl_scores' => [1, 0, 1, 1, 1, 1, 1, 0, 1, 1], // sum 8 -> ติดบ้าน (triggers TAI)
            ],
            [
                'Name_Elderly' => 'มานะ ตั้งใจ',
                'Gender' => 'ชาย',
                'Birthday' => '1945-11-02',
                'Address' => '42 หมู่ 3 ตำบลตัวอย่าง อำเภอตัวอย่าง จังหวัดตัวอย่าง 10000',
                'Phone_Elderly' => '0800000003',
                'adl_scores' => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0], // sum 0 -> ติดเตียง
            ],
        ];

        $adlFields = ['Feeding', 'Grooming', 'Transfer', 'Toilet_use', 'Mobility', 'Dressing', 'Stairs', 'Bathing', 'Bowels', 'Bladder'];

        foreach ($elderlyProfiles as $profile) {
            $elderly = Elderly::create([
                'Name_Elderly' => $profile['Name_Elderly'],
                'Gender' => $profile['Gender'],
                'Birthday' => $profile['Birthday'],
                'Address' => $profile['Address'],
                'Phone_Elderly' => $profile['Phone_Elderly'],
            ]);

            $score = array_sum($profile['adl_scores']);
            $group = $score >= 12 ? 'กลุ่มติดสังคม' : ($score >= 5 ? 'กลุ่มติดบ้าน' : 'กลุ่มติดเตียง');

            $adlData = array_combine($adlFields, $profile['adl_scores']);
            $adl = BarthelAdl::create(array_merge($adlData, [
                'ID_Elderly' => $elderly->ID_Elderly,
                'Name_Elderly' => $elderly->Name_Elderly,
                'ID_User' => $staff->ID_User,
                'Name_User' => $staff->Name_User,
                'Score_ADL' => $score,
                'Group_ADL' => $group,
            ]));

            $careGiver = CareGiver::create([
                'ID_ADL' => $adl->ID_ADL,
                'ID_Elderly' => $elderly->ID_Elderly,
                'Name_CG' => 'ผู้ดูแล (Demo)',
                'Related' => 'บุตร',
                'Phone_CG' => '0800000099',
                'Name_Elderly' => $elderly->Name_Elderly,
                'Birthday' => $elderly->Birthday,
                'Weight' => 55,
                'Height' => 160,
                'Waist' => 78,
                'Address' => $elderly->Address,
                'Group_ADL' => $group,
                'Rights' => 'บัตรทอง',
                'Date_CG' => now()->toDateString(),
                'Consciousness' => 'รู้สึกดี',
                'Vital_signs' => 'BP 120/80 - PR 78',
                'Bedsores' => 'ไม่มี',
                'Pain' => 'ไม่มี',
                'Swelling' => 'ไม่มี',
                'Itchy_rash' => 'ไม่มี',
                'Stiff_joints' => 'ไม่มี',
                'Malnutrition' => 'ไม่มี',
                'Eating' => 'ตักกินเองได้',
                'Swallowing' => 'กลืนได้ปกติ',
                'Defecation' => 'กลั้นได้',
                'Urinary_excretion' => 'กลั้นได้',
                'Taking_medicine' => 'กินสม่ำเสมอ',
                'Emotional_state' => 'ปกติ',
                'Economic_problems' => 'ไม่มี',
                'Social_problems' => 'ไม่มี',
                'Doctor_FU' => 'ไม่มี',
                'Reporter' => $staff->Name_User,
                'Picture' => json_encode([]),
            ]);

            // Fill in the auto-created ScoreTAI (only exists when Score_ADL <= 11)
            $tai = ScoreTAI::where('ID_ADL', $adl->ID_ADL)->first();
            if ($tai) {
                $tai->update([
                    'mobility' => 2,
                    'confuse' => 3,
                    'feed' => 2,
                    'toilet' => 2,
                    'group' => 'กลุ่มติดบ้าน (C2)',
                ]);
            }

            ActivityCaregiver::create([
                'ID_CG' => $careGiver->ID_CG,
                'Date_ACG' => now()->toDateString(),
                'Evaluate' => 'ประเมิน',
                'Dress_the_wound' => '-',
                'Rehabilitate' => '-',
                'Clean_body' => '-',
                'Take_care_medicine' => 'ดูแลเรื่องยา',
                'Take_care_feeding' => '-',
                'Environmental' => '-',
                'Take_exercise' => 'พาออกกำลังกาย',
                'Give_advice_consult' => '-',
                'Take_to_see_a_doctor' => '-',
                'Other' => 'ตัวอย่างข้อมูลสำหรับ Demo',
                'Take_to_make_merit' => '-',
                'Take_to_market' => '-',
                'Take_to_meet_friends' => 'พูดคุยเป็นเพื่อน',
                'Take_to_allowance' => '-',
                'Talk_as_friends' => '-',
                'Other_specified' => 'ตัวอย่างข้อมูลสำหรับ Demo',
                'Problem' => 'ตัวอย่างปัญหาสำหรับสาธิตระบบ',
                'Solution' => 'ตัวอย่างแนวทางแก้ไขสำหรับสาธิตระบบ',
            ]);

            CareInstruction::create([
                'ID_Elderly' => $elderly->ID_Elderly,
                'Date_CI' => now()->toDateString(),
                'Name_Elderly' => $elderly->Name_Elderly,
                'Name_Doctor' => $doctor->Name_User,
                'Name_Staff' => $staff->Name_User,
                'Care_instructions' => "- ตัวอย่างคำแนะนำสำหรับสาธิตระบบ (Demo)\n- แนะนำให้ดื่มน้ำอย่างน้อยวันละ 8 แก้ว",
            ]);
        }
    }
}

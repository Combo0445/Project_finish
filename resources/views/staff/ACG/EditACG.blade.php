<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Activity</title>
    <link href="{{ url('assets/css/argon-dashboard.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- Bootstrap for nav-tabs -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h4 {
            text-align: center;
            margin-bottom: 20px;
        }

        form div {
            margin-bottom: 15px;
        }

        label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        select,
        textarea,
        input[type="date"] {
            width: 100%;
            height: 100%;
            padding: 10px;
            box-sizing: border-box;
        }

        button,
        .btn {
            display: inline-block;
            padding: 10px 20px;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
        }

        .btn-primary {
            background: #3498db;
        }

        .btn-primary:hover {
            background: #2980b9;
        }

        .btn-secondary {
            background: #007BFF;
        }

        .btn-secondary:hover {
            background: #0056b3;
        }

        .hidden {
            display: none;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-check {
            margin-bottom: 15px;
        }

        .form-check-label {
            margin-left: 10px;
        }
    </style>
</head>

<body>
    @include('layout.nav')

    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h4>แก้ไขกิจกรรมการดูแลผู้สูงอายุ (ACG)</h4>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Nav tabs -->
                <ul class="nav nav-tabs" id="activityTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="date-tab" data-toggle="tab" href="#date" role="tab"
                            aria-controls="date" aria-selected="true">วันที่ลงพื้นที่</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="health-tab" data-toggle="tab" href="#health" role="tab"
                            aria-controls="health" aria-selected="false">กิจกรรมด้านสาธารณสุข</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="social-tab" data-toggle="tab" href="#social" role="tab"
                            aria-controls="social" aria-selected="false">กิจกรรมด้านสังคม</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="problem-tab" data-toggle="tab" href="#problem" role="tab"
                            aria-controls="problem" aria-selected="false">ปัญหาและแนวทางแก้ไข</a>
                    </li>
                </ul>
                <br>
                <!-- Tab content -->
                <div class="tab-content" id="activityTabContent">
                    <!-- Date Tab -->
                    <div class="tab-pane fade show active" id="date" role="tabpanel" aria-labelledby="date-tab">
                        <form id="activity-form" action="{{ route('acg.update', ['id' => $activity->ID_ACG]) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <div class="form-group">
                                <label for="ID_Elderly">ชื่อ-สกุลผู้สูงอายุ</label>
                                <input type="text" id="Name_Elderly" name="Name_Elderly" class="form-control" style="height: 45px;" value="{{ $activity->caregiver->Name_Elderly }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="activity_date">ลงเวลากิจกรรมการดูแลผู้สูงอายุ</label>
                                <input type="date" id="activity_date" name="activity_date" class="form-control" value="{{ $activity->Date_ACG }}" required>
                            </div>

                            <div class="form-group">
                                <label for="evaluate">ประเมิน/ติดตามอาการ</label>
                                <select id="evaluate" name="evaluate" class="form-control" style="height: 45px;" required>
                                    <option value="">-- กรุณาเลือก --</option>
                                    <option value="ประเมิน" {{ $activity->Evaluate == 'ประเมิน' ? 'selected' : '' }}>ประเมิน</option>
                                    <option value="ติดตามอาการ" {{ $activity->Evaluate == 'ติดตามอาการ' ? 'selected' : '' }}>ติดตามอาการ</option>
                                </select>
                            </div>
                    </div>

                    <!-- Health Activities Tab -->
                    <div class="tab-pane fade" id="health" role="tabpanel" aria-labelledby="health-tab">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="ทำแผล" id="dress_the_wound" name="dress_the_wound" {{ $activity->Dress_the_wound == 'ทำแผล' ? 'checked' : '' }}>
                            <label class="form-check-label" for="dress_the_wound">ทำแผล</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="ฟื้นฟูสภาพฯ" id="rehabilitate" name="rehabilitate" {{ $activity->Rehabilitate == 'ฟื้นฟูสภาพฯ' ? 'checked' : '' }}>
                            <label class="form-check-label" for="rehabilitate">ฟื้นฟูสภาพฯ</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="ทำความสะอาดร่างกาย" id="clean_body" name="clean_body" {{ $activity->Clean_body == 'ทำความสะอาดร่างกาย' ? 'checked' : '' }}>
                            <label class="form-check-label" for="clean_body">ทำความสะอาดร่างกาย</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="ดูแลเรื่องยา" id="take_care_medicine" name="take_care_medicine" {{ $activity->Take_care_medicine == 'ดูแลเรื่องยา' ? 'checked' : '' }}>
                            <label class="form-check-label" for="take_care_medicine">ดูแลเรื่องยา</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="ดูแลให้อาหาร" id="take_care_feeding" name="take_care_feeding" {{ $activity->Take_care_feeding == 'ดูแลให้อาหาร' ? 'checked' : '' }}>
                            <label class="form-check-label" for="take_care_feeding">ดูแลให้อาหาร</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="การจัดสิ่งแวดล้อม" id="environmental" name="environmental" {{ $activity->Environmental == 'การจัดสิ่งแวดล้อม' ? 'checked' : '' }}>
                            <label class="form-check-label" for="environmental">การจัดสิ่งแวดล้อม</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="พาออกกำลังกาย" id="take_exercise" name="take_exercise" {{ $activity->Take_exercise == 'พาออกกำลังกาย' ? 'checked' : '' }}>
                            <label class="form-check-label" for="take_exercise">พาออกกำลังกาย</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="ให้คำแนะนำ/ปรึกษา" id="give_advice_consult" name="give_advice_consult" {{ $activity->Give_advice_consult == 'ให้คำแนะนำ/ปรึกษา' ? 'checked' : '' }}>
                            <label class="form-check-label" for="give_advice_consult">ให้คำแนะนำ/ปรึกษา</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="พาพบแพทย์" id="take_to_see_a_doctor" name="take_to_see_a_doctor" {{ $activity->Take_to_see_a_doctor == 'พาพบแพทย์' ? 'checked' : '' }}>
                            <label class="form-check-label" for="take_to_see_a_doctor">พาพบแพทย์</label>
                        </div>
                        <div class="form-group">
                            <label for="other_specified">อื่น ๆ ระบุ</label>
                            <input type="text" id="other_specified" name="other_specified" class="form-control" value="{{ $activity->Other }}">
                        </div>
                    </div>

                    <!-- Social Activities Tab -->
                    <div class="tab-pane fade" id="social" role="tabpanel" aria-labelledby="social-tab">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="พาไปทำบุญ" id="take_to_make_merit" name="take_to_make_merit" {{ $activity->Take_to_make_merit == 'พาไปทำบุญ' ? 'checked' : '' }}>
                            <label class="form-check-label" for="take_to_make_merit">พาไปทำบุญ</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="พาไปจ่ายตลาด" id="take_to_market" name="take_to_market" {{ $activity->Take_to_market == 'พาไปจ่ายตลาด' ? 'checked' : '' }}>
                            <label class="form-check-label" for="take_to_market">พาไปจ่ายตลาด</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="พาไปพบเพื่อน" id="take_to_meet_friends" name="take_to_meet_friends" {{ $activity->Take_to_meet_friends == 'พาไปพบเพื่อน' ? 'checked' : '' }}>
                            <label class="form-check-label" for="take_to_meet_friends">พาไปพบเพื่อน</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="พาไปรับเบี้ย" id="take_to_allowance" name="take_to_allowance" {{ $activity->Take_to_allowance == 'พาไปรับเบี้ย' ? 'checked' : '' }}>
                            <label class="form-check-label" for="take_to_allowance">พาไปรับเบี้ย</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="พูดคุยเป็นเพื่อน" id="talk_as_friends" name="talk_as_friends" {{ $activity->Talk_as_friends == 'พูดคุยเป็นเพื่อน' ? 'checked' : '' }}>
                            <label class="form-check-label" for="talk_as_friends">พูดคุยเป็นเพื่อน</label>
                        </div>
                        <div class="form-group">
                            <label for="other_social_specified">อื่น ๆ ระบุ</label>
                            <input type="text" id="other_social_specified" name="other_social_specified" class="form-control" value="{{ $activity->Other_specified }}">
                        </div>
                    </div>

                    <!-- Problem & Solution Tab -->
                    <div class="tab-pane fade" id="problem" role="tabpanel" aria-labelledby="problem-tab">
                        <div class="form-group mt-3">
                            <label for="problem">ปัญหาที่พบ</label>
                            <textarea id="problem" name="problem" class="form-control" rows="3">{{ $activity->Problem }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="solution">แนวทางการแก้ไข</label>
                            <textarea id="solution" name="solution" class="form-control" rows="3">{{ $activity->Solution }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="button-container">
                    <button class="btn btn-success mt-3" type="submit">บันทึก</button>
                    <a href="{{ route('acg.index') }}" class="btn btn-danger mt-3">ยกเลิก</a>
                </div>
            </form>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function handleCheckboxValues(event) {
            event.preventDefault(); // Prevent the form from submitting immediately

            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            const form = event.target; // Get the form element

            checkboxes.forEach(function (checkbox) {
                if (!checkbox.checked) {
                    checkbox.value = "-";
                    checkbox.checked = true;
                }
            });

            form.submit();
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('activity-form').addEventListener('submit', handleCheckboxValues);
        });

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            if (e.target.id === "social-tab") {
                $('button[type="submit"]').removeClass('hidden');
            } else {
                $('button[type="submit"]').addClass('hidden');
            }
        });
    </script>
</body>

</html>

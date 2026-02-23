<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มคำแนะนำ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            margin-top: 20px;
        }

        .card-custom {
            max-width: 600px;
            margin: auto;
        }
    </style>
</head>

<body>
    @include('layout.nav')

    <main class="main-content position-relative h-100 border-radius-lg">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card card-custom mb-4">
                        <div class="card-header pb-0">
                            <h4>เพิ่มคำแนะนำการดูแล</h4>
                        </div>
                        <div class="card-body px-3 pt-3 pb-2">
                            <form action="{{ route('ci.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="Date_CI">วันที่</label>
                                    <input type="date" id="Date_CI" name="Date_CI" class="form-control" value="{{ \Carbon\Carbon::now()->toDateString() }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="Name_Elderly">ชื่อผู้สูงอายุ</label>
                                    <input type="text" id="Name_Elderly" name="Name_Elderly" class="form-control" value="{{ $elderly->Name_Elderly }}" readonly>
                                    <input type="hidden" name="ID_Elderly" value="{{ $elderly->ID_Elderly }}">
                                </div>
                                <div class="form-group">
                                    <label for="Name_Doctor">ชื่อของนายแพทย์</label>
                                    <input type="text" id="Name_Doctor" name="Name_Doctor" class="form-control" value="{{ Auth::user()->Name_User }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="Name_Staff">ชื่อเจ้าหน้าที่</label>
                                    <input type="text" id="Name_Staff" name="Name_Staff" class="form-control" value="{{ $reporter }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="Care_instructions">ข้อมูลคำแนะนำการดูแล</label>
                                    <textarea id="Care_instructions" name="Care_instructions" class="form-control" rows="4" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-success">บันทึก</button>
                                <a href="{{ url()->previous() }}" class="btn btn-danger">ยกเลิก</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="{{ url('assets/js/argon-dashboard.js') }}"></script>
    <script src="{{ url('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('assets/js/popper.min.js') }}"></script>
    <script src="{{ url('assets/js/bootstrap-notify.js') }}"></script>
    <script src="{{ url('assets/js/chartjs.min.js') }}"></script>
    <script src="{{ url('assets/js/Chart.extension.js') }}"></script>
    <script src="{{ url('assets/js/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ url('assets/js/smooth-scrollbar.min.js') }}"></script>
</body>

</html>

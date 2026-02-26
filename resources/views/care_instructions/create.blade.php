@extends('layouts.app')

@section('title', 'เพิ่มคำแนะนำการดูแล')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4" style="max-width: 600px; margin: auto;">
                <div class="card-header pb-0">
                    <h4>เพิ่มคำแนะนำการดูแล</h4>
                </div>
                <div class="card-body px-3 pt-3 pb-2">
                    <form action="{{ route('care_instructions.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="Date_CI">วันที่</label>
                            <input type="date" id="Date_CI" name="Date_CI" class="form-control"
                                value="{{ \Carbon\Carbon::now()->toDateString() }}" required>
                        </div>
                        <div class="form-group">
                            <label for="Name_Elderly">ชื่อผู้สูงอายุ</label>
                            <input type="text" id="Name_Elderly" name="Name_Elderly" class="form-control"
                                value="{{ $elderly->Name_Elderly }}" readonly>
                            <input type="hidden" name="ID_Elderly" value="{{ $elderly->ID_Elderly }}">
                        </div>
                        <div class="form-group">
                            <label for="Name_Doctor">ชื่อของนายแพทย์</label>
                            <input type="text" id="Name_Doctor" name="Name_Doctor" class="form-control"
                                value="{{ Auth::user()->Name_User }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="Name_Staff">ชื่อเจ้าหน้าที่</label>
                            <input type="text" id="Name_Staff" name="Name_Staff" class="form-control"
                                value="{{ $reporter }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="Care_instructions">ข้อมูลคำแนะนำการดูแล</label>
                            <textarea id="Care_instructions" name="Care_instructions" class="form-control" rows="4"
                                required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">บันทึก</button>
                        <a href="{{ url()->previous() }}" class="btn btn-danger">ยกเลิก</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
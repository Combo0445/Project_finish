@extends('layouts.app')

@section('title', 'แก้ไขคำแนะนำการดูแล')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4" style="max-width: 600px; margin: auto;">
                <div class="card-header pb-0">
                    <h4>แก้ไขคำแนะนำการดูแล</h4>
                </div>
                <div class="card-body px-3 pt-3 pb-2">
                    <form action="{{ route('care_instructions.update', $careInstruction->ID_CI) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="Date_CI">วันที่</label>
                            <input type="date" id="Date_CI" name="Date_CI" class="form-control"
                                value="{{ $careInstruction->Date_CI }}" required>
                        </div>
                        <div class="form-group">
                            <label for="Name_Elderly">ชื่อผู้สูงอายุ</label>
                            <input type="text" id="Name_Elderly" name="Name_Elderly" class="form-control"
                                value="{{ $careInstruction->Name_Elderly }}" readonly>
                            <input type="hidden" name="ID_Elderly" value="{{ $careInstruction->ID_Elderly }}">
                        </div>
                        <div class="form-group">
                            <label for="Name_Doctor">ชื่อของหมอ</label>
                            <input type="text" id="Name_Doctor" name="Name_Doctor" class="form-control"
                                value="{{ $careInstruction->Name_Doctor }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="Name_Staff">ชื่อเจ้าหน้าที่</label>
                            <input type="text" id="Name_Staff" name="Name_Staff" class="form-control"
                                value="{{ $careInstruction->Name_Staff }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="Care_instructions">ข้อมูลคำแนะนำการดูแล</label>
                            <textarea id="Care_instructions" name="Care_instructions" class="form-control" rows="4"
                                required>{{ $careInstruction->Care_instructions }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-success">บันทึกการเปลี่ยนแปลง</button>
                        <a href="{{ url()->previous() }}" class="btn btn-danger">ยกเลิก</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
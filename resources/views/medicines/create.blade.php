@extends('layout.layout-staff')

@section('content')
    <div class="row pt-5 mt-5">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow" style="border-radius: 20px;">
                <div class="card-header bg-primary text-white" style="border-radius: 20px 20px 0 0;">
                    <h4 class="mb-0">เพิ่มข้อมูลยา (Add Medicine)</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('medicines.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">ชื่อยา <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required
                                value="{{ old('name') }}">
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">รูปแบบยา</label>
                            <select class="form-select" id="type" name="type">
                                <option value="">-- เลือกรุปแบบยา --</option>
                                <option value="Tablet (ยาเม็ด)" {{ old('type') == 'Tablet (ยาเม็ด)' ? 'selected' : '' }}>
                                    Tablet (ยาเม็ด)</option>
                                <option value="Capsule (ยาแคปซูล)" {{ old('type') == 'Capsule (ยาแคปซูล)' ? 'selected' : '' }}>Capsule (ยาแคปซูล)</option>
                                <option value="Syrup (ยาน้ำ)" {{ old('type') == 'Syrup (ยาน้ำ)' ? 'selected' : '' }}>Syrup
                                    (ยาน้ำ)</option>
                                <option value="Injection (ยาฉีด)" {{ old('type') == 'Injection (ยาฉีด)' ? 'selected' : '' }}>
                                    Injection (ยาฉีด)</option>
                                <option value="Cream (ยาทา)" {{ old('type') == 'Cream (ยาทา)' ? 'selected' : '' }}>Cream
                                    (ยาทา)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="stock" class="form-label">จำนวนสต๊อก <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="stock" name="stock" required min="0"
                                value="{{ old('stock', 0) }}">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">สรรพคุณ/รายละเอียด</label>
                            <textarea class="form-control" id="description" name="description"
                                rows="3">{{ old('description') }}</textarea>
                        </div>

                        <div class="text-center mt-4">
                            <a href="{{ route('medicines.index') }}" class="btn btn-secondary">ยกเลิก</a>
                            <button type="submit" class="btn btn-success">บันทึกข้อมูล</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@extends('layout.layout-staff')

@section('content')
    <div class="row pt-5 mt-5">
        <div class="col-lg-8 offset-lg-2">
            <h2 class="mb-4">รับยาเข้าคลัง: {{ $medicine->name }}</h2>

            <div class="card shadow">
                <div class="card-body bg-white p-4" style="border-radius: 20px;">
                    <form action="{{ route('medicines.lots.store', $medicine->id) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="lot_number" class="form-label">หมายเลขล็อตบัญชียา (Lot Number) <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="lot_number" id="lot_number" class="form-control" required
                                value="{{ old('lot_number', 'L' . date('Ymd')) }}">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="mfd_date" class="form-label">วันที่ผลิต (MFD Date)</label>
                                <input type="date" name="mfd_date" id="mfd_date" class="form-control"
                                    value="{{ old('mfd_date') }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="exp_date" class="form-label">วันหมดอายุ (EXP Date)</label>
                                <input type="date" name="exp_date" id="exp_date" class="form-control"
                                    value="{{ old('exp_date') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="stock" class="form-label">จำนวนที่รับเข้า (Quantity) <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="stock" id="stock" class="form-control" min="1" required
                                    value="{{ old('stock') }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="cost_price" class="form-label">ราคาทุนต่อหน่วย (Cost Price)</label>
                                <input type="number" step="0.01" name="cost_price" id="cost_price" class="form-control"
                                    min="0" value="{{ old('cost_price') }}">
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <a href="{{ route('medicines.index') }}" class="btn btn-secondary me-2">ยกเลิก</a>
                            <button type="submit" class="btn btn-primary">บันทึกรับยาเข้าคลัง</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
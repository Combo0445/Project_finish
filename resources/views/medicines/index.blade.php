@extends('layout.layout-staff')

@section('content')
<div class="row pt-5 mt-5">
    <div class="col-lg-12">
        <h2 class="mb-4">คลังยา (Medicine Inventory)</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="mb-3 text-end">
            <a href="{{ route('medicines.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> เพิ่มรายการยา
            </a>
        </div>

        <div class="card shadow">
            <div class="card-body bg-white p-4" style="border-radius: 20px;">
                <table class="table table-bordered table-striped" id="medicineTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>ชื่อยา</th>
                            <th>รูปแบบยา</th>
                            <th>สรรพคุณ</th>
                            <th>จำนวนคงเหลือ</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($medicines as $key => $medicine)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $medicine->name }}</td>
                            <td>{{ $medicine->type }}</td>
                            <td>{{ $medicine->description }}</td>
                            <td>
                                @if($medicine->stock > 10)
                                    <span class="badge bg-success">{{ $medicine->stock }}</span>
                                @else
                                    <span class="badge bg-danger">{{ $medicine->stock }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('medicines.lots.create', $medicine->id) }}" class="btn btn-info btn-sm text-white"><i class="fas fa-box-open"></i> รับเข้าสต็อก</a>
                                <a href="{{ route('medicines.edit', $medicine->id) }}" class="btn btn-warning btn-sm">แก้ไข</a>
                                <form action="{{ route('medicines.destroy', $medicine->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบยานี้?')">ลบ</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">ไม่พบข้อมูลยาในระบบ</td>
                        </tr>
                        @end forelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        $('#medicineTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/th.json"
            }
        });
    });
</script>
@endsection

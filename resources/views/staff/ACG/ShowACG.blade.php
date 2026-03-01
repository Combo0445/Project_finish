@extends('layouts.app')

@section('title', 'ACG Information')

@section('content')
    <x-card>
        <x-slot name="header">
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 w-100">
                <h4 class="mb-0">การประเมินกิจกรรมการดูแลผู้สูงอายุ (ACG)</h4>
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <form action="{{ route('acg.index') }}" method="GET" class="d-flex gap-2 align-items-center me-2">
                        <input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}">
                        <button type="submit" class="btn btn-sm btn-dark mb-0">ค้นหา</button>
                        @if(request('date'))
                            <a href="{{ route('acg.index') }}" class="btn btn-sm btn-link mb-0 text-secondary">ล้าง</a>
                        @endif
                    </form>
                    <a href="{{ route('activities.create') }}" class="btn btn-primary btn-sm mb-0">
                        <i class="fas fa-plus"></i> เพิ่ม ACG
                    </a>
                    <a href="{{ route('report.all.acg') }}" target="_blank" class="btn btn-success btn-sm mb-0">
                        <i class="fas fa-print"></i>
                    </a>
                </div>
            </div>
        </x-slot>

        <div class="table-responsive p-0">
            <table id="acgTable" class="table align-items-center mb-0">
                <thead>
                    <tr>
                        <th class="text-center">วันที่</th>
                        <th class="text-center">ชื่อผู้สูงอายุ</th>
                        <th class="text-center">ชื่อผู้ดูแลผู้สูงอายุ</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($activities as $activity)
                        <tr>
                            <td class="text-center">{{ $activity->Date_ACG }}</td>
                            <td class="text-center">{{ $activity->caregiver->Name_Elderly }}</td>
                            <td class="text-center">{{ $activity->caregiver->Name_CG }}</td>
                            <td class="text-center">
                                <a href="{{ route('report.acg', ['id' => $activity->ID_ACG]) }}" target="_blank"
                                    class="btn btn-success btn-sm">ออกรายงาน</a>
                                <a href="{{ route('acg.edit', ['id' => $activity->ID_ACG]) }}"
                                    class="btn btn-sm {{ is_null($activity->Evaluate) ? 'btn-success' : 'btn-warning' }}">
                                    <i class="fas fa-edit"></i>
                                    {{ is_null($activity->Evaluate) ? 'เพิ่มแบบประเมิน' : 'แก้ไขแบบประเมิน' }}
                                </a>
                                <form id="delete-acg-form-{{ $activity->ID_ACG }}"
                                    action="{{ route('acg.destroy', ['id' => $activity->ID_ACG]) }}" method="POST"
                                    style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmDelete('{{ $activity->ID_ACG }}')">ลบ</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3 px-3">
                {{ $activities->links() }}
            </div>
        </div>
    </x-card>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#acgTable').DataTable({
                "language": {
                    "paginate": { "previous": "ก่อนหน้า", "next": "ถัดไป" },
                    "search": "ค้นหา : ",
                    "lengthMenu": "แสดง _MENU_ รายการ",
                    "zeroRecords": "ไม่พบข้อมูล",
                    "info": "กำลังแสดงรายการ _START_ ถึง _END_ จากทั้งหมด _TOTAL_ รายการ",
                    "infoEmpty": "ไม่พบข้อมูล",
                    "infoFiltered": "(filtered from _MAX_ total records)"
                },
                "dom": '<"row"<"col-sm-12 col-md-12"l><"col-sm-12 col-md-12"f>>t<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7 d-flex justify-content-end"p>>'
            });
        });

        function confirmDelete(id) {
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: "คุณจะไม่สามารถย้อนกลับได้หลังจากลบ!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-acg-form-' + id).submit();
                }
            });
        }

        // Script removed to use server-side PDF instead for better formatting consistency (18px font)
    </script>
@endpush
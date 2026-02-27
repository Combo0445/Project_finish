@extends('layouts.app')

@section('title', 'Performance Reports')

@section('content')
    <x-card>
        <x-slot name="header">
            <h4>Performance Reports</h4>
            <div class="d-flex gap-2">
                <a href="{{ route('performanceReport.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> เพิ่ม Report
                </a>
            </div>
        </x-slot>

        <div class="table-responsive p-0">
            <table id="reportTable" class="table align-items-center mb-0">
                <thead>
                    <tr>
                        <th class="text-center">วันที่</th>
                        <th class="text-center">ผู้สูงอายุ</th>
                        <th class="text-center">CG</th>
                        <th class="text-center">เจ้าหน้าที่</th>
                        <th class="text-center">สภาวะ</th>
                        <th class="text-center">กิจกรรม</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($performanceReports as $r)
                        <tr>
                            <td class="text-center">{{ \Carbon\Carbon::parse($r->Date)->format('Y-m-d H:i') }}</td>
                            <td class="text-center">{{ $r->elderly->Name_Elderly }}</td>
                            <td class="text-center">{{ optional($r->caregiver)->Name_CG }}</td>
                            <td class="text-center">{{ $r->user->Name_User }}</td>
                            <td class="text-center">{{ $r->State }}</td>
                            <td class="text-center">{{ Str::limit($r->Activity, 30) }}</td>
                            <td class="text-center">
                                <a href="{{ route('performanceReport.exportPDF', $r->id) }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-print"></i> ออกรายงาน
                                </a>
                                <a href="{{ route('performanceReport.edit', $r->id) }}" class="btn btn-warning btn-sm">แก้ไข</a>
                                <form id="delete-form-{{ $r->id }}" action="{{ route('performanceReport.destroy', $r->id) }}"
                                    method="POST" style="display:inline-block;">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmDelete('{{ $r->id }}')">ลบ</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">
                {{ $performanceReports->links() }}
            </div>
        </div>
    </x-card>
@endsection

@push('scripts')
    <script>
        $(function () {
            $('#reportTable').DataTable({
                language: {
                    paginate: { previous: "ก่อนหน้า", next: "ถัดไป" },
                    search: "ค้นหา : ",
                    lengthMenu: "แสดง _MENU_ รายการ",
                    zeroRecords: "ไม่พบข้อมูล",
                    info: "แสดง _START_ ถึง _END_ จาก _TOTAL_ รายการ",
                    infoEmpty: "ไม่พบข้อมูล",
                },
                dom: '<"row"<"col-sm-12 col-md-12"l><"col-sm-12 col-md-12"f>>t<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7 d-flex justify-content-end"p>>'
            });
        });

        function confirmDelete(id) {
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: "ข้อมูลนี้จะหายไปถาวร!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
@endpush
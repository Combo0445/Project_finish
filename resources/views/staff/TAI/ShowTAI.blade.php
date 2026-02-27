@extends('layouts.app')

@section('title', 'TAI Information')

@section('content')
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <x-card>
        <x-slot name="header">
            <h4>รายงานผลการประเมินผู้สูงอายุ (TAI)</h4>
            <div class="d-flex gap-2">
                <a href="{{ route('report.all.tai') }}" target="_blank" class="btn btn-success">
                    <i class="fas fa-print"></i>
                </a>
                <a href="{{ route('tai.export') }}" class="btn btn-primary btn-sm">Export Excel</a>
            </div>
        </x-slot>

        <div class="table-responsive p-0">
            <table id="taiTable" class="table align-items-center mb-0">
                <thead>
                    <tr>
                        <th class="text-center">วันที่</th>
                        <th class="text-center">ชื่อผู้สูงอายุ</th>
                        <th class="text-center">ชื่อผู้ดูแลผู้สูงอายุ</th>
                        <th class="text-center">ประเภทกลุ่ม</th>
                        <th class="text-center">ประเภทกลุ่มผู้สูงอายุ</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tai as $item)
                        <tr>
                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($item->updated_at)->locale('th')->translatedFormat('d F Y') }}
                            </td>
                            <td class="text-center">{{ $item->elderly->Name_Elderly ?? 'ยังไม่ได้ประเมิน' }}</td>
                            <td class="text-center">{{ $item->user->Name_User ?? 'ยังไม่ได้ประเมิน' }}</td>
                            <td class="text-center">{{ $item->group ?? 'ยังไม่ได้ประเมิน' }}</td>
                            <td class="text-center">
                                @php
                                    $group = $item->group;
                                    if (in_array($group, ['B5', 'B4', 'B3'])) {
                                        $displayText = 'กลุ่มปกติ';
                                    } elseif (in_array($group, ['C4', 'C3', 'C2'])) {
                                        $displayText = 'กลุ่มติดบ้าน';
                                    } elseif (in_array($group, ['I3', 'I2', 'I1'])) {
                                        $displayText = 'กลุ่มติดเตียง';
                                    } else {
                                        $displayText = 'ยังไม่ได้ประเมิน';
                                    }
                                @endphp
                                {{ $displayText }}
                            </td>
                            <td class="text-center">
                                <a href="{{ route('report.tai.pdf', ['id' => $item->id]) }}" target="_blank"
                                    class="btn btn-sm btn-info">
                                    ออกรายงาน
                                </a>
                                <a href="{{ route('tai.edit', ['id' => $item->id]) }}"
                                    class="btn btn-sm {{ is_null($item->group) ? 'btn-success' : 'btn-warning' }}">
                                    <i class="fas fa-edit"></i>
                                    {{ is_null($item->group) ? 'เพิ่มแบบประเมิน' : 'แก้ไขแบบประเมิน' }}
                                </a>
                                <form id="delete-tai-form-{{ $item->id }}"
                                    action="{{ route('tai.destroy', ['id' => $item->id]) }}" method="POST"
                                    style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmDelete('{{ $item->id }}')">ลบ</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">
                {{ $tai->links() }}
            </div>
        </div>
    </x-card>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#taiTable').DataTable({
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
                    document.getElementById('delete-tai-form-' + id).submit();
                }
            });
        }

        // Script removed to use server-side PDF instead for better formatting consistency (18px font)
    </script>
@endpush
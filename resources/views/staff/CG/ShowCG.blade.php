@extends('layouts.app')

@section('title', 'CG Information')

@section('content')
    <x-card>
        <x-slot name="header">
            <h4>รายงานผลการปฏิบัติงานผู้ดูแลผู้สูงอายุ (CG)</h4>
            <div class="d-flex gap-2">
                <a href="{{ route('cg.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> เพิ่ม CG
                </a>
                <a href="{{ route('report.all.cg') }}" target="_blank" class="btn btn-success">
                    <i class="fas fa-print"></i>
                </a>
                <a href="{{ route('cg.export') }}" class="btn btn-primary btn-sm">Export Excel</a>
            </div>
        </x-slot>

        <div class="table-responsive p-0">
            <table id="cgTable" class="table align-items-center mb-0">
                <thead>
                    <tr>
                        <th class="text-center">วันที่</th>
                        <th class="text-center">ชื่อผู้สูงอายุ</th>
                        <th class="text-center">ชื่อผู้ดูแลผู้สูงอายุ</th>
                        <th class="text-center">ประเภทกลุ่มผู้สูงอายุ</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($careGivers as $cg)
                        <tr>
                            <td class="text-center">{{ $cg->Date_CG }}</td>
                            <td class="text-center">{{ $cg->Name_Elderly }}</td>
                            <td class="text-center">{{ $cg->Name_CG }}</td>
                            <td class="text-center">
                                @php
                                    $group = $cg->Group_ADL;
                                    if (in_array($group, ['B3'])) {
                                        $displayText = 'ติดบ้าน กลุ่มที่ 1';
                                    } else if (in_array($group, ['C4', 'C3', 'C2'])) {
                                        $displayText = 'ติดบ้าน กลุ่มที่ 2';
                                    } else if (in_array($group, ['I3', 'I2', 'I1'])) {
                                        $displayText = 'ติดเตียง กลุ่มที่ 1';
                                    } else {
                                        $displayText = 'ยังไม่ได้ประเมิน';
                                    }
                                @endphp
                                {{ $displayText }}
                            </td>
                            <td class="text-center">
                                <a href="{{ route('report.cg', ['id' => $cg->ID_CG]) }}" target="_blank"
                                    class="btn btn-success btn-sm">
                                    ออกรายงาน
                                </a>
                                <a href="{{ route('cg.edit', ['id' => $cg->ID_CG]) }}"
                                    class="btn btn-sm {{ is_null($cg->Name_CG) ? 'btn-success' : 'btn-warning' }}">
                                    <i class="fas fa-edit"></i>
                                    {{ is_null($cg->Name_CG) ? 'เพิ่มแบบประเมิน' : 'แก้ไขแบบประเมิน' }}
                                </a>
                                <form id="delete-cg-form-{{ $cg->ID_CG }}"
                                    action="{{ route('cg.destroy', ['id' => $cg->ID_CG]) }}" method="POST"
                                    style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmDelete('{{ $cg->ID_CG }}')">ลบ</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">
                {{ $careGivers->links() }}
            </div>
        </div>
    </x-card>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#cgTable').DataTable({
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
                    document.getElementById('delete-cg-form-' + id).submit();
                }
            });
        }

        // Script removed to use server-side PDF instead for better formatting consistency (18px font)
    </script>
@endpush
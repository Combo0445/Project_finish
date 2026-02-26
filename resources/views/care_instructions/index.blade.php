@extends('layouts.app')

@section('title', 'Care Instructions')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h4>ข้อมูลคำแนะนำการดูแล</h4>

                    @if(Auth::user()->Type_Personnel == 'Doctor')
                        <button id="generate-pdf" class="btn btn-success">
                            <i class="fas fa-print"></i> พิมพ์รายงาน
                        </button>
                    @endif
                </div>
                <div class="card-body px-3 pt-3 pb-2">

                    <!-- Filters -->
                    <div class="mb-3 d-flex justify-content-end">
                        <form method="GET" action="{{ route('care_instructions.index') }}"
                            class="d-flex align-items-center">
                            @if(request()->has('unconfirmed') && request('unconfirmed') == 'true')
                                <input type="hidden" name="unconfirmed" value="false">
                                <button type="submit" class="btn btn-info btn-sm m-0">แสดงทั้งหมด</button>
                            @else
                                <input type="hidden" name="unconfirmed" value="true">
                                <button type="submit"
                                    class="btn btn-warning btn-sm m-0">แสดงเฉพาะรายการที่ยังไม่ได้ยืนยัน</button>
                            @endif
                        </form>
                    </div>

                    <div class="table-responsive p-0">
                        <table id="ciTable" class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center">วันที่</th>
                                    <th class="text-center">ชื่อผู้สูงอายุ</th>
                                    <th class="text-center">ที่อยู่</th>
                                    <th class="text-center">เบอร์โทร</th>
                                    <th class="text-center">ชื่อแพทย์</th>
                                    @if(Auth::user()->Type_Personnel == 'Doctor')
                                        <th class="text-center">ชื่อเจ้าหน้าที่</th>
                                    @endif
                                    <th class="text-center text-wrap" style="max-width:300px;">คำแนะนำการดูแล</th>
                                    <th class="text-center">สถานะ</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($careInstructions as $ci)
                                    <tr>
                                        <td class="text-center">{{ $ci->Date_CI }}</td>
                                        <td class="text-center">{{ $ci->Name_Elderly }}</td>
                                        <td class="text-center">{{ $ci->elderly->Address }}</td>
                                        <td class="text-center">{{ $ci->elderly->Phone_Elderly }}</td>
                                        <td class="text-center">{{ $ci->Name_Doctor }}</td>

                                        @if(Auth::user()->Type_Personnel == 'Doctor')
                                            <td class="text-center">{{ $ci->Name_Staff }}</td>
                                        @endif

                                        <td class="text-center text-wrap" style="max-width:300px;">{{ $ci->Care_instructions }}
                                        </td>

                                        <td class="text-center">
                                            @if($ci->Confirm)
                                                <span class="badge bg-success">ยืนยันแล้ว</span>
                                            @else
                                                <span class="badge bg-warning">รอยืนยัน</span>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            <!-- Both can search location -->
                                            <a href="{{ route('search-location', ['id' => $ci->elderly->ID_Elderly]) }}"
                                                target="_blank" class="btn btn-info btn-sm">ที่อยู่</a>

                                            <!-- Staff Level Controls -->
                                            @if(Auth::user()->Type_Personnel == 'Staff')
                                                @if(empty($ci->Confirm))
                                                    <form action="{{ route('care_instructions.confirm', ['id' => $ci->ID_CI]) }}"
                                                        method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-success btn-sm">ยืนยัน</button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('care_instructions.unconfirm', ['id' => $ci->ID_CI]) }}"
                                                        method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-secondary btn-sm">ยกเลิก</button>
                                                    </form>
                                                @endif
                                            @endif

                                            <!-- Doctor Level Controls -->
                                            @if(Auth::user()->Type_Personnel == 'Doctor')
                                                <a href="{{ route('care_instructions.edit', ['id' => $ci->ID_CI]) }}"
                                                    class="btn btn-warning btn-sm">แก้ไข</a>
                                                <form id="delete-ci-form-{{ $ci->ID_CI }}"
                                                    action="{{ route('care_instructions.destroy', ['id' => $ci->ID_CI]) }}"
                                                    method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        onclick="confirmDelete('{{ $ci->ID_CI }}')">ลบ</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $careInstructions->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Include DataTables JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#ciTable').DataTable({
                "language": {
                    "paginate": { "previous": "ก่อนหน้า", "next": "ถัดไป" },
                    "search": "ค้นหา : ",
                    "lengthMenu": "แสดง _MENU_ รายการ",
                    "zeroRecords": "ไม่พบข้อมูล",
                    "info": "กำลังแสดงรายการ _START_ ถึง _END_ จากทั้งหมด _TOTAL_ รายการ",
                    "infoEmpty": "ไม่พบข้อมูล",
                    "infoFiltered": "(กรองจากทั้งหมด _MAX_ รายการ)"
                },
                "dom": '<"row"<"col-sm-12 col-md-12"l><"col-sm-12 col-md-12"f>>t<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-2 d-flex justify-content-center"p>>'
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
                    document.getElementById('delete-ci-form-' + id).submit();
                }
            });
        }
    </script>
@endpush
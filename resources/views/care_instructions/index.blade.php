@extends('layouts.app')

@section('title', 'Care Instructions')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h4>ข้อมูลคำแนะนำการดูแล</h4>

                    @if(Auth::user()->Type_Personnel == 'Doctor')
                        <a href="{{ route('report.ci.all.pdf', request()->query()) }}" target="_blank"
                            class="badge rounded-pill bg-gradient-success border-0 px-3 py-2"
                            style="cursor: pointer; font-size: 14px; text-decoration: none;">
                            <i class="fas fa-print me-2"></i> พิมพ์รายงานทั้งหมด (PDF)
                        </a>
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

                                        <td class="text-center text-wrap" style="max-width:300px;">
                                            {{ \Illuminate\Support\Str::limit($ci->Care_instructions, 50) }}
                                        </td>

                                        <td class="text-center">
                                            <div class="d-flex flex-column align-items-center gap-1">
                                                <!-- Plan Acknowledgment Status -->
                                                @if($ci->Confirm)
                                                    <a href="{{ route('report.ci.pdf', ['id' => $ci->ID_CI]) }}" target="_blank"
                                                        class="badge rounded-pill bg-gradient-success" title="คลิกเพื่อพิมพิ์รายงาน">
                                                        แผน: ยืนยันแล้ว
                                                    </a>
                                                @else
                                                    <span class="badge rounded-pill bg-gradient-secondary">แผน: รอยืนยัน</span>
                                                @endif

                                                <!-- Medication Status (Only if meds exist) -->
                                                @if($ci->prescriptions->count() > 0)
                                                    @if($ci->Confirm_Medication)
                                                        <span class="badge rounded-pill bg-gradient-info">ยา: เตรียมแล้ว</span>
                                                    @else
                                                        <span class="badge rounded-pill bg-gradient-warning">ยา: รอเตรียม</span>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>

                                        <td class="text-center">
                                            <!-- View Details Button -->
                                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                                data-target="#viewCiModal{{ $ci->ID_CI }}">
                                                ดูรายละเอียด
                                            </button>

                                            @php 
                                                $role = session('impersonate_role', Auth::user()->Type_Personnel);
                                            @endphp

                                            <!-- Pharmacist Action: Dispense -->
                                            @if(($role == 'Pharmacist' || $role == 'Admin') && $ci->prescriptions->count() > 0 && empty($ci->Confirm_Medication))
                                                <form action="{{ route('care_instructions.dispense', ['id' => $ci->ID_CI]) }}"
                                                    method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-primary btn-sm">จ่ายยา/ตัดสต็อก</button>
                                                </form>
                                            @endif

                                            <!-- Staff Action: Acknowledge (Blocked by medication if exists) -->
                                            @if(($role == 'Staff' || $role == 'Admin') && empty($ci->Confirm))
                                                @php
                                                    $isBlockedByMed = ($ci->prescriptions->count() > 0 && empty($ci->Confirm_Medication));
                                                @endphp
                                                <form action="{{ route('care_instructions.confirm', ['id' => $ci->ID_CI]) }}"
                                                    method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-success btn-sm" {{ $isBlockedByMed ? 'disabled title=กรุณารอเภสัชกรจ่ายยาก่อน' : '' }}>
                                                        ยืนยันรับทราบ
                                                    </button>
                                                </form>
                                            @endif

                                            <!-- Unconfirm Actions -->
                                            @if($role == 'Admin' || ($role == 'Staff' && $ci->Confirm) || ($role == 'Pharmacist' && $ci->Confirm_Medication))
                                                <form action="{{ route('care_instructions.unconfirm', ['id' => $ci->ID_CI]) }}"
                                                    method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('คุณแน่ใจหรือไม่ที่จะยกเลิกการยืนยัน?')">
                                                        ยกเลิก
                                                    </button>
                                                </form>
                                            @endif

                                            <!-- Location Button -->
                                            @if($role != 'Pharmacist')
                                                <a href="{{ route('search-location', ['id' => $ci->elderly->ID_Elderly]) }}"
                                                    target="_blank" class="btn btn-secondary btn-sm">ที่อยู่</a>
                                            @endif

                                            <!-- Doctor Level Controls -->
                                            @if($role == 'Doctor')
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

                        <!-- View Modals (Moved outside table for DataTables compatibility) -->
                        @foreach($careInstructions as $ci)
                            <div class="modal fade" id="viewCiModal{{ $ci->ID_CI }}" tabindex="-1"
                                aria-labelledby="viewCiModalLabel{{ $ci->ID_CI }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header bg-gradient-info text-white">
                                            <h5 class="modal-title text-white" id="viewCiModalLabel{{ $ci->ID_CI }}">
                                                รายละเอียดคำแนะนำ: {{ $ci->Name_Elderly }}</h5>
                                            <button type="button" class="close text-dark" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body text-start border-bottom-0">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <strong>วันที่:</strong> {{ current(explode(' ', $ci->Date_CI)) }}<br>
                                                    <strong>แพทย์ผู้สั่ง:</strong> {{ $ci->Name_Doctor }}<br>
                                                    <strong>เจ้าหน้าที่รับผิดชอบ:</strong> {{ $ci->Name_Staff ?? 'ไม่ระบุ' }}
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>สถานะแผนการดูแล:</strong>
                                                    @if($ci->Confirm)
                                                        <span class="text-success fw-bold">ยืนยันรับทราบแล้ว</span>
                                                    @else
                                                        <span class="text-warning fw-bold">รอการยืนยัน</span>
                                                    @endif
                                                    <br>
                                                    @if($ci->prescriptions->count() > 0)
                                                        <strong>สถานะยา:</strong>
                                                        @if($ci->Confirm_Medication)
                                                            <span class="text-info fw-bold">เตรียมยา/ตัดสต็อกแล้ว</span>
                                                        @else
                                                            <span class="text-danger fw-bold">รอเภสัชกรเตรียมยา</span>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="card bg-light mb-3">
                                                <div class="card-body">
                                                    <h6 class="card-title">คำแนะนำจากแพทย์</h6>
                                                    <p class="card-text" style="white-space: pre-line;">
                                                        {{ $ci->Care_instructions }}</p>
                                                </div>
                                            </div>

                                            @if($ci->prescriptions && $ci->prescriptions->count() > 0)
                                                <h6>รายการยาที่ต้องเตรียม/จ่าย</h6>
                                                <table class="table table-bordered table-sm mb-0">
                                                    <thead class="table-primary">
                                                        <tr>
                                                            <th class="text-center">ชื่อยา</th>
                                                            <th class="text-center">จำนวนที่สั่ง</th>
                                                            <th class="text-center">วิธีใช้ (Dosage)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($ci->prescriptions as $rx)
                                                            <tr>
                                                                <td>{{ $rx->medicine ? $rx->medicine->name : 'ไม่ทราบชื่อยา' }}</td>
                                                                <td class="text-center">{{ $rx->amount }}
                                                                    {{ $rx->medicine ? $rx->medicine->type : '' }}</td>
                                                                <td>{{ $rx->dosage ?: '-' }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @else
                                                <div class="alert alert-secondary text-sm mb-0">ไม่มีรายการสั่งยาในคำแนะนำนี้</div>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <a href="{{ route('report.ci.pdf', ['id' => $ci->ID_CI]) }}" target="_blank"
                                                class="btn btn-success">
                                                <i class="fas fa-print me-2"></i> พิมพ์รายงาน
                                            </a>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

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
    {{-- html2pdf removed --}}

    <script>
        $(document).ready(function () {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'สำเร็จ',
                    text: '{!! session('success') !!}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'ข้อผิดพลาด',
                    text: '{!! session('error') !!}',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'ตกลง'
                });
            @endif
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
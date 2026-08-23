@extends('layouts.app')

@section('title', 'ADL Information')

@push('styles')
    <style>
        .modal-xl {
            max-width: 75% !important;
        }
    </style>
@endpush

@section('content')
    <x-card>
        <x-slot name="header">
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 w-100">
                <h4 class="mb-0">ประเมินความสามารถในการดำเนินชีวิตประจำวัน (ADL)</h4>
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <form action="{{ route('adl.index') }}" method="GET" class="d-flex flex-wrap gap-2 align-items-center me-2">
                        <input type="text" name="search" class="form-control form-control-sm"
                            placeholder="ชื่อผู้สูงอายุ" style="max-width: 180px;" value="{{ request('search') }}">
                        <div class="input-group input-group-sm" style="max-width: 260px;">
                            <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                            <span class="input-group-text">ถึง</span>
                            <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
                        </div>
                        <button type="submit" class="btn btn-sm btn-dark mb-0">ค้นหา</button>
                        @if(request()->hasAny(['search', 'date_from', 'date_to']))
                            <a href="{{ route('adl.index') }}" class="btn btn-sm btn-link mb-0 text-secondary">ล้าง</a>
                        @endif
                    </form>
                    <a href="{{ route('adl.create') }}" class="btn btn-primary btn-sm mb-0">
                        <i class="fas fa-plus"></i> เพิ่ม ADL
                    </a>
                    <a href="{{ route('report.all.adl') }}" target="_blank" class="btn btn-success btn-sm mb-0">
                        <i class="fas fa-print"></i>
                    </a>
                    <a href="{{ route('adl.export') }}" class="btn btn-primary btn-sm mb-0">Export Excel</a>
                </div>
            </div>
        </x-slot>

        <div class="table-responsive p-0">
            <table id="adlTable" class="table align-items-center mb-0">
                <thead>
                    <tr>
                        <th class="text-center">วันที่</th>
                        <th class="text-center">ชื่อผู้สูงอายุ</th>
                        <th class="text-center">ชื่อเจ้าหน้าที่</th>
                        <th class="text-center">คะแนนการประเมิน ADL</th>
                        <th class="text-center">ประเภทกลุ่ม ADL</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($adls as $adl)
                        <tr>
                            <td class="text-center">{{ $adl->created_at ? $adl->created_at->format('Y-m-d') : '' }}</td>
                            <td class="text-center">
                                <a href="{{ route('elderly.profile', $adl->ID_Elderly) }}">{{ $adl->Name_Elderly }}</a>
                            </td>
                            <td class="text-center">{{ $adl->Name_User }}</td>
                            <td class="text-center">{{ $adl->Score_ADL }}</td>
                            <td class="text-center">{{ $adl->Group_ADL }}</td>
                            <td class="text-center">
                                <a href="{{ route('report.adl.pdf', ['id' => $adl->ID_ADL]) }}" class="btn btn-success btn-sm"
                                    target="_blank">
                                    ออกรายงาน
                                </a>
                                <button class="btn btn-info btn-sm" data-toggle="modal"
                                    data-target="#adlModal-{{ $adl->ID_ADL }}">ดูข้อมูล</button>
                                <a href="{{ route('adl.edit', ['id' => $adl->ID_ADL]) }}"
                                    class="btn btn-warning btn-sm">แก้ไข</a>
                                <button type="button" class="btn btn-danger btn-sm"
                                    onclick="confirmDelete('{{ $adl->ID_ADL }}')">ลบ</button>
                                <form id="delete-adl-form-{{ $adl->ID_ADL }}"
                                    action="{{ route('adl.destroy', ['id' => $adl->ID_ADL]) }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">
                {{ $adls->links() }}
            </div>
        </div>
    </x-card>

    <!-- ADL Modals -->
    @foreach ($adls as $adl)
        <div class="modal fade" id="adlModal-{{ $adl->ID_ADL }}" tabindex="-1"
            aria-labelledby="adlModalLabel-{{ $adl->ID_ADL }}" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="adlModalLabel-{{ $adl->ID_ADL }}">
                            ข้อมูลประเมินความสามารถในการดำเนินชีวิตประจำวัน (ADL) ของ {{ $adl->Name_Elderly }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>หัวข้อการประเมิน</th>
                                    <th>คะแนน</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1. การรับประทานอาหาร (Feeding)</td>
                                    <td>{{ $adl->Feeding }}</td>
                                </tr>
                                <tr>
                                    <td>2. การล้างหน้า แต่งตัว (Grooming)</td>
                                    <td>{{ $adl->Grooming }}</td>
                                </tr>
                                <tr>
                                    <td>3. การลุกนั่งจากที่นอน (Transfer)</td>
                                    <td>{{ $adl->Transfer }}</td>
                                </tr>
                                <tr>
                                    <td>4. การใช้ห้องน้ำ (Toilet Use)</td>
                                    <td>{{ $adl->Toilet_use }}</td>
                                </tr>
                                <tr>
                                    <td>5. การเคลื่อนที่ภายในบ้าน (Mobility)</td>
                                    <td>{{ $adl->Mobility }}</td>
                                </tr>
                                <tr>
                                    <td>6. การสวมใส่เสื้อผ้า (Dressing)</td>
                                    <td>{{ $adl->Dressing }}</td>
                                </tr>
                                <tr>
                                    <td>7. การขึ้นลงบันได (Stairs)</td>
                                    <td>{{ $adl->Stairs }}</td>
                                </tr>
                                <tr>
                                    <td>8. การอาบน้ำ (Bathing)</td>
                                    <td>{{ $adl->Bathing }}</td>
                                </tr>
                                <tr>
                                    <td>9. การควบคุมการถ่ายอุจจาระ (Bowels)</td>
                                    <td>{{ $adl->Bowels }}</td>
                                </tr>
                                <tr>
                                    <td>10. การควบคุมการถ่ายปัสสาวะ (Bladder)</td>
                                    <td>{{ $adl->Bladder }}</td>
                                </tr>
                                <tr class="table-info">
                                    <td><strong>คะแนนรวม</strong></td>
                                    <td><strong>{{ $adl->Score_ADL }}</strong></td>
                                </tr>
                                <tr class="table-primary">
                                    <td><strong>กลุ่ม ADL</strong></td>
                                    <td><strong>{{ $adl->Group_ADL }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
    {{-- html2pdf script removed --}}
    <script>
        $(document).ready(function () {
            $('#adlTable').DataTable({
                "language": {
                    "paginate": {
                        "previous": "ก่อนหน้า",
                        "next": "ถัดไป"
                    },
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
                    document.getElementById('delete-adl-form-' + id).submit();
                }
            });
        }

        // PDF Generation Logic
        document.getElementById('generate-pdf')?.addEventListener('click', function () {
            var filteredData = $('#adlTable').DataTable().rows({ filter: 'applied' }).data().toArray();
            if (filteredData.length === 0) {
                Swal.fire('ไม่พบข้อมูลที่ตรงกับการค้นหา', '', 'error');
                return;
            }

            var reportContent = document.createElement('div');
            reportContent.innerHTML = `
                                <style>
                                    * { font-family: 'Open Sans', Arial, sans-serif !important; color: black !important; background-color: white !important; }
                                    h5 { font-size: 20px; margin: 0; text-align: center; }
                                    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 14px; }
                                    th, td { padding: 8px; text-align: center; border: 1px solid black; }
                                    td { font-size: 12px; }
                                </style>
                                <h5>รายงานความสามารถในการดำเนินชีวิตประจำวัน (ADL)</h5>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>วันที่</th>
                                            <th>ชื่อผู้สูงอายุ</th>
                                            <th>ชื่อเจ้าหน้าที่</th>
                                            <th>คะแนน ADL</th>
                                            <th>กลุ่ม ADL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${filteredData.map(row => `
                                            <tr>
                                                <td>${row[0]}</td>
                                                <td>${row[1]}</td>
                                                <td>${row[2]}</td>
                                                <td>${row[3]}</td>
                                                <td>${row[4]}</td>
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                            `;

            const opt = {
                margin: 0.5,
                filename: 'รายงาน_ADL_ทั้งหมด.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
            };

            html2pdf().set(opt).from(reportContent).output('blob').then(function (pdfBlob) {
                var pdfUrl = URL.createObjectURL(pdfBlob);
                var pdfWindow = window.open();
                pdfWindow.location.href = pdfUrl;
            });
        });

        // Script removed to use server-side PDF instead for better formatting consistency (18px font)
    </script>
@endpush
@extends('layouts.app')

@section('title', 'ACG Information')

@section('content')
    <x-card>
        <x-slot name="header">
            <h4>การประเมินกิจกรรมการดูแลผู้สูงอายุ (ACG)</h4>
            <div class="d-flex gap-2">
                <a href="{{ route('activities.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> เพิ่ม ACG
                </a>
                <button id="generate-pdf" class="btn btn-success">
                    <i class="fas fa-print"></i>
                </button>
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
                                <a href="javascript:void(0);" onclick="generatePdf({{ $activity->ID_ACG }})"
                                    class="btn btn-success btn-sm">ออกรายงาน</a>
                                <a href="{{ route('cg.edit', ['id' => $activity->ID_ACG]) }}"
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

        function generatePdf(id) {
            fetch(`{{ route('report.acg', ':id') }}`.replace(':id', id))
                .then(response => response.text())
                .then(data => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(data, 'text/html');
                    const element = doc.querySelector('.container');
                    const style = document.createElement('style');
                    style.innerHTML = `
                        * { font-family: 'Open Sans', Arial, sans-serif !important; color: black !important; background-color: white !important; }
                        h5 { text-align: center; margin-bottom: 20px; font-size: 24px; }
                        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                        table, th, td { border: 1px solid black; }
                        th, td { padding: 8px; text-align: left; }
                    `;
                    element.appendChild(style);
                    const opt = {
                        margin: 0.5,
                        filename: `รายงาน_ACG_บุคคล_${id}.pdf`,
                        jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
                    };
                    html2pdf().set(opt).from(element).output('blob').then(function (pdfBlob) {
                        const pdfUrl = URL.createObjectURL(pdfBlob);
                        const pdfWindow = window.open();
                        pdfWindow.location.href = pdfUrl;
                    });
                });
        }

        document.getElementById('generate-pdf').addEventListener('click', function () {
            var filteredData = $('#acgTable').DataTable().rows({ filter: 'applied' }).data().toArray();
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
                </style>
                <h5>รายงานการประเมินกิจกรรมการดูแลผู้สูงอายุ (ACG)</h5>
                <table>
                    <thead>
                        <tr>
                            <th>วันที่</th>
                            <th>ชื่อผู้สูงอายุ</th>
                            <th>ชื่อผู้ดูแลผู้สูงอายุ</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${filteredData.map(row => `<tr><td>${row[0]}</td><td>${row[1]}</td><td>${row[2]}</td></tr>`).join('')}
                    </tbody>
                </table>
            `;

            const opt = {
                margin: 0.5,
                filename: 'รายงานกิจกรรมผู้สูงอายุ_ทั้งหมด.pdf',
                jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
            };

            html2pdf().set(opt).from(reportContent).output('blob').then(function (pdfBlob) {
                var pdfUrl = URL.createObjectURL(pdfBlob);
                var pdfWindow = window.open();
                pdfWindow.location.href = pdfUrl;
            });
        });
    </script>
@endpush
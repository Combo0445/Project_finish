<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Performance Reports</title>
    <!-- Argon Dashboard CSS -->
    <link href="{{ url('assets/css/argon-dashboard.css') }}" rel="stylesheet">
    <link href="{{ url('assets/css/nucleo-icons.css') }}" rel="stylesheet">
    <link href="{{ url('assets/css/nucleo-svg.css') }}" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <!-- html2pdf.js for PDF export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <style>
        .container { margin-top: 20px; }
        .table th, .table td { vertical-align: middle; }
    </style>
</head>

<body>
    @include('layout.nav')

    <main class="main-content position-relative h-100 border-radius-lg">
        <div class="container-fluid py-4">

            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                            <h4>Performance Reports</h4>
                            <div class="d-flex gap-2">
                                <a href="{{ route('performanceReport.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> เพิ่ม Report
                                </a>
                                {{-- <a href="{{ route('performanceReport.export') }}" class="btn btn-primary btn-sm">
                                    Export Excel
                                </a> --}}
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
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
                                                       <i class="fas fa-print"></i> สร้าง PDF
                                                     </a>
                                                    <a href="{{ route('performanceReport.edit', $r->id) }}"
                                                       class="btn btn-warning btn-sm">แก้ไข</a>
                                                    <form id="delete-form-{{ $r->id }}"
                                                          action="{{ route('performanceReport.destroy', $r->id) }}"
                                                          method="POST" style="display:inline-block;">
                                                        @csrf @method('DELETE')
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                                onclick="confirmDelete('{{ $r->id }}')">
                                                            ลบ
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $reports->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script src="{{ url('assets/js/argon-dashboard.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(function() {
            $('#reportTable').DataTable({
                language: {
                    paginate: { previous: "ก่อนหน้า", next: "ถัดไป" },
                    search: "ค้นหา : ",
                    lengthMenu: "แสดง _MENU_ รายการ",
                    zeroRecords: "ไม่พบข้อมูล",
                    info: "แสดง _START_ ถึง _END_ จาก _TOTAL_ รายการ",
                    infoEmpty: "ไม่พบข้อมูล",
                },
                dom: '<"row"<"col-sm-12 col-md-12"l><"col-sm-12 col-md-12"f>>t<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-2 d-flex justify-content-center"p>>'
            });
        });

        function confirmDelete(id) {
            if (!confirm('ยืนยันการลบใช่หรือไม่?')) return;
            document.getElementById('delete-form-'+id).submit();
        }
    </script>
</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CG Information</title>
    <!-- Argon Dashboard CSS -->
    <link href="{{ url('assets/css/argon-dashboard.css') }}" rel="stylesheet">
    <link href="{{ url('assets/css/nucleo-icons.css') }}" rel="stylesheet">
    <link href="{{ url('assets/css/nucleo-svg.css') }}" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    {{--  pdf  --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <style>
        .container {
            margin-top: 20px;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }
    </style>
</head>

<body>
    @include('layout.nav')

        <div class="container-fluid py-4">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
            <!-- CG Information Card -->
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                            <h4>รายงานผลการประเมินผู้สูงอายุ (TAI)</h4>
                            <div class="d-flex gap-2">
                                <button id="generate-pdf" class="btn btn-success">
                                    <i class="fas fa-print"></i>
                                </button>
                                <a href="{{ route('cg.export') }}" class="btn btn-primary btn-sm">Export Excel</a>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table id="cgTable" class="table align-items-center mb-0">
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
                                                <td class="text-center">{{ \Carbon\Carbon::parse($item->updated_at)->locale('th')->translatedFormat('d F Y') }}</td>
                                                <td class="text-center">
                                                    {{ $item->elderly->Name_Elderly ?? 'ยังไม่ได้ประเมิน' }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $item->user->Name_User ?? 'ยังไม่ได้ประเมิน' }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $item->group ?? 'ยังไม่ได้ประเมิน' }}
                                                </td>
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
                                                    <a href="{{ route('tai.edit', ['id' => $item->id]) }}"
                                                        class="btn btn-sm {{ is_null($item->group) ? 'btn-success' : 'btn-warning' }}">
                                                        <i class="fas fa-edit"></i>
                                                        {{ is_null($item->group) ? 'เพิ่มแบบประเมิน' : 'แก้ไขแบบประเมิน' }}
                                                    </a>
                                                    <form id="delete-tai-form-{{ $item->id }}"
                                                        action="{{ route('tai.destroy', ['id' => $item->id]) }}"
                                                        method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            onclick="confirmDelete('{{ $item->id }}')">
                                                            <i class="fas fa-trash"></i> ลบ
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- Argon Dashboard JS -->
    <script src="{{ url('assets/js/argon-dashboard.js') }}"></script>
    <script src="{{ url('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('assets/js/popper.min.js') }}"></script>
    <script src="{{ url('assets/js/bootstrap-notify.js') }}"></script>
    <script src="{{ url('assets/js/chartjs.min.js') }}"></script>
    <script src="{{ url('assets/js/Chart.extension.js') }}"></script>
    <script src="{{ url('assets/js/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ url('assets/js/smooth-scrollbar.min.js') }}"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#cgTable').DataTable({
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
                    document.getElementById('delete-tai-form-' + id).submit();
                }
            });
        }


        document.getElementById('generate-pdf').addEventListener('click', function() {
            // Fetch the filtered data from the DataTable (if any filters are applied)
            var filteredData = $('#cgTable').DataTable().rows({
                filter: 'applied'
            }).data().toArray();

            if (filteredData.length === 0) {
                Swal.fire('ไม่พบข้อมูลที่ตรงกับการค้นหา', '', 'error');
                return;
            }

            // Create a hidden div to hold the content
            var reportContent = document.createElement('div');
            reportContent.innerHTML = `
                <style>
                    * {
                        font-family: 'Open Sans', Arial, sans-serif !important;
                        color: black !important;
                        background-color: white !important;
                    }

                    h5 {
                        font-size: 20px;
                        margin: 0;
                    }

                    img {
                        height: 80px;
                        margin-right: 10px;
                    }

                    /* กำหนดความกว้างของตาราง */
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-bottom: 20px;
                        font-size: 14px; /* เพิ่มขนาดฟอนต์ในตาราง */
                    }

                    /* กำหนดสไตล์สำหรับ th และ td */
                    th, td {
                        padding: 8px; /* เพิ่ม padding ให้ดูใหญ่ขึ้น */
                        text-align: center;
                        border: 1px solid black;
                    }

                    /* ขนาดฟอนต์ใน td */
                    td {
                        font-size: 12.5px;
                        text-align: center;
                    }

                    .page-break {
                        page-break-before: always; /* บังคับขึ้นหน้าใหม่ */
                    }
                </style>

                <h5>
                    <img src="{{ url('images/Logo.png') }}" alt="Logo">
                    รายงานการปฏิบัติงานผู้ดูแลผู้สูงอายุ (CG)
                </h5>
                <br>
                <table>
                    <thead>
                        <tr>
                            <th style="width: 20%;">วันที่</th>
                            <th style="width: 20%;">ชื่อผู้สูงอายุ</th>
                            <th style="width: 20%;">ชื่อผู้ดูแลผู้สูงอายุ</th>
                            <th style="width: 20%;">ประเภทกลุ่มผู้สูงอายุ</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${filteredData.map((cg, index) => `
                                    ${(index % 12 === 0 && index !== 0) ? `
                                <tr class="page-break">
                                    <th style="width: 20%;">วันที่</th>
                                    <th style="width: 20%;">ชื่อผู้สูงอายุ</th>
                                    <th style="width: 20%;">ชื่อผู้ดูแลผู้สูงอายุ</th>
                                    <th style="width: 20%;">ประเภทกลุ่มผู้สูงอายุ</th>
                                </tr>` : ''}
                                    <tr>
                                        <td>${cg[0]}</td>
                                        <td>${cg[1]}</td>
                                        <td>${cg[2]}</td>
                                        <td>${cg[3]}</td>
                                    </tr>`).join('')}
                    </tbody>
                </table>
            `;

            setTimeout(function() {
                // Configure options for generating the PDF
                var opt = {
                    margin: 0.5,
                    filename: 'รายงาน_CG.pdf',
                    image: {
                        type: 'jpeg',
                        quality: 0.98
                    },
                    html2canvas: {
                        scale: 2
                    },
                    jsPDF: {
                        unit: 'in',
                        format: 'letter',
                        orientation: 'portrait'
                    }
                };

                // Generate the PDF and open it in a new window
                html2pdf().set(opt).from(reportContent).output('blob').then(function(pdfBlob) {
                    var pdfUrl = URL.createObjectURL(pdfBlob);
                    var pdfWindow = window.open();
                    pdfWindow.location.href = pdfUrl;
                });
            });
        });

        function generatePdf(id) {
            // Fetch the content from the specific report-cg/{id} URL
            fetch(`{{ route('report.cg', ':id') }}`.replace(':id', id))
                .then(response => response.text()) // Fetch HTML as text
                .then(data => {
                    // Convert the fetched HTML into a DOM object
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(data, 'text/html');
                    const element = doc.querySelector('.container'); // Get the content

                    // Add CSS to set the font back to the template's original font
                    const style = document.createElement('style');
                    style.innerHTML = `
                            * {
                                font-family: 'Open Sans', Arial, sans-serif !important;
                                color: black !important;
                                background-color: white !important;
                            }

                            body {
                                width: 210mm;
                                height: 297mm;
                                margin: 0;
                                padding: 20mm;
                                font-family: Arial, sans-serif;
                                font-size: 12px;
                            }

                            .container {
                                padding: 10mm;
                                border-radius: 5px;
                            }

                            h5 {
                                text-align: center;
                                margin-bottom: 20px;
                                font-size: 24px;
                            }

                            table {
                                width: 100%;
                                border-collapse: collapse;
                                margin-bottom: 20px;
                                page-break-inside: avoid;
                            }

                            th, td {
                                border: 1px solid black;
                                padding: 8px;
                                text-align: left;
                            }

                            th {
                                background-color: #f2f2f2;
                            }

                            .page-break {
                                page-break-before: always; /* บังคับขึ้นหน้าใหม่ */
                            }
                        `;
                    element.appendChild(style);

                    // Configure options for generating the PDF
                    var opt = {
                        margin: 0.5,
                        filename: 'รายงาน_CG_บุคคล.pdf',
                        image: {
                            type: 'jpeg',
                            quality: 0.98
                        },
                        html2canvas: {
                            scale: 2
                        },
                        jsPDF: {
                            unit: 'in',
                            format: 'letter',
                            orientation: 'portrait'
                        }
                    };

                    // Generate the PDF and open it in a new window
                    html2pdf().set(opt).from(element).output('blob').then(function(pdfBlob) {
                        var pdfUrl = URL.createObjectURL(pdfBlob);
                        var pdfWindow = window.open();
                        pdfWindow.location.href = pdfUrl;
                    });
                })
                .catch(error => console.error('Error fetching report data:', error));
        }
    </script>
</body>

</html>

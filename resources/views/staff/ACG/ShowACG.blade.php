<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACG Information</title>
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

    <main class="main-content position-relative h-100 border-radius-lg">
        <div class="container-fluid py-4">

            <!-- ACG Information Card -->
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                            <h4>การประเมินกิจกรรมการดูแลผู้สงอายุ (ACG)</h4>
                            <div class="d-flex gap-2">
                            <a href="{{ route('activities.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> เพิ่ม ACG
                            </a>
                            <button id="generate-pdf" class="btn btn-success">
                                <i class="fas fa-print"></i>
                            </button>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
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
                                                    <a href="javascript:void(0);" onclick="generatePdf({{ $activity->ID_ACG }})" class="btn btn-success btn-sm">ออกรายงาน</a>
                                                    {{--  <a href="{{ route('acg.edit', ['id' => $activity->ID_ACG]) }}"
                                                        class="btn btn-warning btn-sm">แก้ไข</a>  --}}
                                                        <a href="{{ route('cg.edit', ['id' => $activity->ID_ACG]) }}"
                                                            class="btn btn-sm {{ is_null($activity->Evaluate) ? 'btn-success' : 'btn-warning' }}">
                                                            <i class="fas fa-edit"></i>
                                                            {{ is_null($activity->Evaluate) ? 'เพิ่มแบบประเมิน' : 'แก้ไขแบบประเมิน' }}
                                                        </a>
                                                        <form id="delete-acg-form-{{ $activity->ID_ACG }}"
                                                            action="{{ route('acg.destroy', ['id' => $activity->ID_ACG]) }}"
                                                            method="POST" style="display:inline-block;">
                                                          @csrf
                                                          @method('DELETE')
                                                          <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{ $activity->ID_ACG }}')">ลบ</button>
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
    </main>

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
            $('#acgTable').DataTable({
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
                    document.getElementById('delete-acg-form-' + id).submit();
                }
            });
        }

        document.getElementById('generate-pdf').addEventListener('click', function () {
            // Fetch the filtered data from the DataTable (if any filters are applied)
            var filteredData = $('#acgTable').DataTable().rows({ filter: 'applied' }).data().toArray();

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

                    .adl-details {
                        font-size: 11.5px;
                        text-align: left;
                        padding: 14px;
                    }

                    .page-break {
                        page-break-before: always; /* บังคับขึ้นหน้าใหม่ */
                    }
                </style>

                <h5>
                    <img src="{{ url('images/Logo.png') }}" alt="Logo">
                    รายงานการประเมินกิจกรรมการดูแลผู้สูงอายุ (ACG)
                </h5>
                <br>
                <table>
                    <thead>
                        <tr>
                            <th style="width: 20%;">วันที่</th>
                            <th style="width: 30%;">ชื่อผู้สูงอายุ</th>
                            <th style="width: 30%;">ชื่อผู้ดูแลผู้สูงอายุ</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${filteredData.map((acg, index) => `
                            ${(index % 12 === 0 && index !== 0) ? `
                                <tr class="page-break">
                                    <th style="width: 20%;">วันที่</th>
                                    <th style="width: 30%;">ชื่อผู้สูงอายุ</th>
                                    <th style="width: 30%;">ชื่อผู้ดูแลผู้สูงอายุ</th>
                                </tr>` : ''}
                            <tr>
                                <td>${acg[0]}</td>
                                <td>${acg[1]}</td>
                                <td>${acg[2]}</td>
                            </tr>`).join('')}
                    </tbody>
                </table>
            `;

            setTimeout(function () {
                // Configure options for generating the PDF
                var opt = {
                    margin: 0.5,
                    filename: 'รายงานกิจกรรมผู้สูงอายุ.pdf',
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2 },
                    jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
                };

                // Generate the PDF and open it in a new window
                html2pdf().set(opt).from(reportContent).output('blob').then(function (pdfBlob) {
                    var pdfUrl = URL.createObjectURL(pdfBlob);
                    var pdfWindow = window.open();
                    pdfWindow.location.href = pdfUrl;
                });
            });
        });


            // Add event listeners for each "generate-pdf" button
            function generatePdf(id) {
                // Fetch the content from the specific report-acg/{id} URL
                fetch(`{{ route('report.acg', ':id') }}`.replace(':id', id))
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

                            <style>
                                body {
                                    width: 210mm;
                                    height: 297mm;
                                    margin: 0;
                                    padding: 20mm;
                                    font-family: Arial, sans-serif;
                                    font-size: 14px;


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

                                .section-title {
                                    font-size: 18px;
                                    margin-top: 20px;
                                    margin-bottom: 10px;
                                    font-weight: bold;

                                }

                                table {
                                    width: 100%;
                                    border-collapse: collapse;
                                    margin-bottom: 20px;
                                }


                                th,
                                td {
                                    border: 1px solid black;
                                }

                                th,
                                td {
                                    padding: 8px;
                                    text-align: left;
                                }

                                th {
                                    background-color: #f2f2f2;
                                }

                                .info {
                                    margin-bottom: 15px;
                                }

                                .info label {
                                    font-weight: bold;
                                }

                                .info span {
                                    display: block;
                                    margin-top: 5px;
                                }

                                .page-break {
                                    page-break-before: always;
                                }
                            </style>
                        `;
                        element.appendChild(style);

                        // Configure options for generating the PDF
                        var opt = {
                            margin: 0.5,
                            filename: `รายงาน_ACG_บุคคล_${id}.pdf`,
                            image: { type: 'jpeg', quality: 0.98 },
                            html2canvas: { scale: 2 },
                            jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
                        };

                        // Generate the PDF and open it in a new window
                        html2pdf().set(opt).from(element).output('blob').then(function (pdfBlob) {
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

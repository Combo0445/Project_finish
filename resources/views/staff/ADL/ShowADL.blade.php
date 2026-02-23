<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADL Information</title>
    <!-- Include DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        {{--  pdf  --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <style>
        .modal-xl {
            max-width: 75% !important;
        }
    </style>
</head>

<body>

    @include('layout.nav')

    <main class="main-content position-relative h-100 border-radius-lg">
        <div class="container-fluid py-4">

            <!-- ADL Information Card -->
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                            <h4>ประเมินความสามารถในการดำเนินชีวิตประจำวัน (ADL)</h4>
                            <div class="d-flex gap-2">
                            <a href="{{ route('adl.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> เพิ่ม ADL
                            </a>
                            <button id="generate-pdf" class="btn btn-success">
                                <i class="fas fa-print"></i>
                            </button>
                            <a href="{{ route('adl.export') }}" class="btn btn-primary btn-sm">Export Excel</a>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
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
                                                <td class="text-center">{{ $adl->Name_Elderly }}</td>
                                                <td class="text-center">{{ $adl->Name_User }}</td>
                                                <td class="text-center">{{ $adl->Score_ADL }}</td>
                                                <td class="text-center">{{ $adl->Group_ADL }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('report.adl', ['id' => $adl->ID_ADL]) }}" class="btn btn-success btn-sm generate-pdf2">
                                                        ออกรายงาน
                                                    </a>
                                                    <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#adlModal-{{ $adl->ID_ADL }}">ดูข้อมูล</button>
                                                    <a href="{{ route('adl.edit', ['id' => $adl->ID_ADL]) }}"
                                                        class="btn btn-warning btn-sm">แก้ไข</a>
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{ $adl->ID_ADL }}')">ลบ</button>
                                                        <form id="delete-adl-form-{{ $adl->ID_ADL }}" action="{{ route('adl.destroy', ['id' => $adl->ID_ADL]) }}" method="POST" style="display: none;">
                                                            @csrf
                                                            @method('DELETE')
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

            <!-- ADL Modals -->
            @foreach ($adls as $adl)
                <div class="modal fade" id="adlModal-{{ $adl->ID_ADL }}" tabindex="-1" aria-labelledby="adlModalLabel-{{ $adl->ID_ADL }}" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="adlModalLabel-{{ $adl->ID_ADL }}">ข้อมูลประเมินความสามารถในการดำเนินชีวิตประจำวัน (ADL) ของ {{ $adl->Name_Elderly }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <table class="table">
                                    <tr>
                                        <th>การรับประทานอาหาร</th>
                                        <td>{{ \App\Models\BarthelAdl::getAdlDescription('feeding', $adl->Feeding) }}</td>
                                    </tr>
                                    <tr>
                                        <th>การดูแลร่างกาย</th>
                                        <td>{{ \App\Models\BarthelAdl::getAdlDescription('grooming', $adl->Grooming) }}</td>
                                    </tr>
                                    <tr>
                                        <th>การย้ายตัว</th>
                                        <td>{{ \App\Models\BarthelAdl::getAdlDescription('transfer', $adl->Transfer) }}</td>
                                    </tr>
                                    <tr>
                                        <th>การใช้ห้องน้ำ</th>
                                        <td>{{ \App\Models\BarthelAdl::getAdlDescription('toilet_use', $adl->Toilet_use) }}</td>
                                    </tr>
                                    <tr>
                                        <th>การเคลื่อนที่ภายในห้องหรือบ้าน</th>
                                        <td>{{ \App\Models\BarthelAdl::getAdlDescription('mobility', $adl->Mobility) }}</td>
                                    </tr>
                                    <tr>
                                        <th>การสวมใส่เสื้อผ้า</th>
                                        <td>{{ \App\Models\BarthelAdl::getAdlDescription('dressing', $adl->Dressing) }}</td>
                                    </tr>
                                    <tr>
                                        <th>การขึ้นลงบันได 1 ชั้น</th>
                                        <td>{{ \App\Models\BarthelAdl::getAdlDescription('stairs', $adl->Stairs) }}</td>
                                    </tr>
                                    <tr>
                                        <th>การอาบน้ำ</th>
                                        <td>{{ \App\Models\BarthelAdl::getAdlDescription('bathing', $adl->Bathing) }}</td>
                                    </tr>
                                    <tr>
                                        <th>การกลั้นการถ่ายอุจจาระในระยะ 1 สัปดาห์ที่ผ่านมา</th>
                                        <td>{{ \App\Models\BarthelAdl::getAdlDescription('bowels', $adl->Bowels) }}</td>
                                    </tr>
                                    <tr>
                                        <th>การกลั้นปัสสาวะในระยะ 1 สัปดาห์ที่ผ่านมา</th>
                                        <td>{{ \App\Models\BarthelAdl::getAdlDescription('bladder', $adl->Bladder) }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">ปิด</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </main>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Include DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
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
                    document.getElementById('delete-adl-form-' + id).submit();
                }
            });
        }

        document.getElementById('generate-pdf').addEventListener('click', function () {
            // Fetch the filtered data from the DataTable (if any filters are applied)
            var filteredData = $('#adlTable').DataTable().rows({ filter: 'applied' }).data().toArray();

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
                    รายงานการประเมินประเมินความสามารถในการดำเนินชีวิตประจำวัน
                </h5>
                <br>
                <table>
                    <thead>
                        <tr>
                            <th style="width: 20%;">ชื่อผู้สูงอายุ</th>
                            <th style="width: 20%;">ชื่อเจ้าหน้าที่</th>
                            <th style="width: 20%;">คะแนนการประเมิน ADL</th>
                            <th style="width: 20%;">ประเภทกลุ่ม ADL</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${filteredData.map((adl, index) => `
                            ${(index % 12 === 0 && index !== 0) ? `
                                <tr class="page-break">
                                    <th style="width: 20%;">ชื่อผู้สูงอายุ</th>
                                    <th style="width: 20%;">ชื่อเจ้าหน้าที่</th>
                                    <th style="width: 20%;">คะแนนการประเมิน ADL</th>
                                    <th style="width: 20%;">ประเภทกลุ่ม ADL</th>
                                </tr>` : ''}
                            <tr>
                                <td>${adl[0]}</td>
                                <td>${adl[1]}</td>
                                <td>${adl[2]}</td>
                                <td>${adl[3]}</td>
                            </tr>`).join('')}
                    </tbody>
                </table>
            `;

            setTimeout(function () {
                // Configure options for generating the PDF
                var opt = {
                    margin: 0.5,
                    filename: 'รายงาน_ADL.pdf',
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

        document.querySelectorAll('.generate-pdf2').forEach(button => {
            button.addEventListener('click', function (event) {
                event.preventDefault();

                const url = this.href; // Get the URL to fetch the report by ID

                fetch(url)
                    .then(response => response.text()) // Fetch HTML content as text
                    .then(data => {
                        // Convert the fetched HTML into a DOM object
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(data, 'text/html');
                        const element = doc.querySelector('.container'); // Get the content

                        // Add inline CSS for PDF styling
                        const style = document.createElement('style');
                        style.innerHTML = `
                            * {
                                font-family: 'Arial', sans-serif !important;
                                color: black !important;
                                background-color: white !important;
                            }
                            <style>
                                body {
                                display: flex;
                                justify-content: center;  /* จัดกึ่งกลางในแนวนอน */
                                align-items: center;      /* จัดกึ่งกลางในแนวตั้ง */
                                height: 100vh;            /* ใช้ 100% ของความสูงหน้ากระดาษ */
                                margin: 0;                /* ลบ margin ของ body */
                                }

                                img {
                                max-width: 100px;  /* กำหนดขนาดรูปภาพ */
                                height: auto;
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

                                table,
                                th,
                                td {
                                    border: 1px solid black;
                                }

                                th,
                                td {
                                    padding: 8px;
                                    text-align: left;
                                }

                               td {
                                    font-size: 12px;
                                }

                                .questions {
                                    margin-top: 20px;
                                    page-break-inside: avoid;
                                }

                                .questions table {
                                    width: 100%;
                                    border-collapse: collapse;
                                }

                                .questions th,
                                .questions td {
                                    border: 1px solid black;
                                    padding: 8px;
                                }

                                .questions th {
                                    background-color: #f2f2f2;
                                }

                                .page-break {
                                    page-break-after: always;
                                }
                            </style>
                        `;
                        element.appendChild(style);

                        // Configure PDF options
                        const opt = {
                            margin: 0.5,
                            filename: 'รายงาน_ADL.pdf',
                            image: { type: 'jpeg', quality: 0.98 },
                            html2canvas: { scale: 2 },
                            jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
                        };

                        // Create the PDF and open it in a new window
                        html2pdf().set(opt).from(element).output('blob').then(function (pdfBlob) {
                            const pdfUrl = URL.createObjectURL(pdfBlob);
                            const pdfWindow = window.open();
                            pdfWindow.location.href = pdfUrl;
                        });
                    })
                    .catch(error => console.error('Error fetching report data:', error));
            });
        });


    </script>
</body>

</html>

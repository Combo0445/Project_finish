<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <title>จัดการคำแนะนำการดูแลที่ยืนยันแล้ว</title>
</head>
<body>

    @include('layout.nav')

    <!-- Confirmed Care Instructions Card -->
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                            <h4>คำแนะนำการดูแลที่ยืนยันแล้ว</h4>
                            <a href="javascript:void(0);" id="generate-pdf" class="btn btn-success ml-2">
                                <i class="fas fa-print"></i>
                            </a>
                        </div>
                        <div class="card-body px-3 pt-3 pb-2">
                            <div class="table-responsive p-0">
                                <table id="ciTableConfirmed" class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">วันที่</th>
                                            <th class="text-center">ชื่อผู้สูงอายุ</th>
                                            <th class="text-center">ที่อยู่</th>
                                            <th class="text-center">เบอร์โทร</th>
                                            <th class="text-center">ชื่อแพทย์</th>
                                            <th class="text-center">ชื่อเจ้าหน้าที่</th>
                                            <th class="text-center">คำแนะนำ</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($careInstructions as $ci)
                                            @if(!empty($ci->Confirm))
                                                <tr>
                                                    <td class="text-center">{{ $ci->Date_CI }}</td>
                                                    <td class="text-center">{{ $ci->Name_Elderly }}</td>
                                                    <td class="text-center">{{ $ci->elderly->Address }}</td>
                                                    <td class="text-center">{{ $ci->elderly->Phone_Elderly }}</td>
                                                    <td class="text-center">{{ $ci->Name_Doctor }}</td>
                                                    <td class="text-center">{{ $ci->Name_Staff }}</td>
                                                    <td class="text-center">{{ $ci->Care_instructions }}</td>
                                                    <td class="text-center">
                                                        <a href="{{ route('search-location', ['id' => $ci->elderly->ID_Elderly]) }}" target="_blank" class="btn btn-info btn-sm">ค้นหาที่อยู่</a>
                                                        <button class="generate-single-report btn btn-success btn-sm" data-id="{{ $ci->ID_CI }}">
                                                            ออกรายงาน
                                                        </button>
                                                        <form action="{{ route('ci.unconfirm', ['id' => $ci->ID_CI]) }}" method="POST" style="display:inline-block;">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="btn btn-danger btn-sm">ยกเลิกยืนยัน</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

</body>
<!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#ciTableConfirmed').DataTable({
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

        document.getElementById('generate-pdf').addEventListener('click', function () {
            // Fetch the filtered data from the DataTable (if any filters are applied)
            var filteredData = $('#ciTableConfirmed').DataTable().rows({ filter: 'applied' }).data().toArray();

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
                        font-size: 18px;
                        margin-bottom: 20px;
                    }

                    img {
                        height: 80px;
                        margin-right: 10px;
                        vertical-align: middle;
                    }

                    /* Customize table styling */
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-bottom: 20px;
                        font-size: 13px;
                    }

                    /* Customize table headers and cells */
                    th, td {
                        padding: 3px;
                        text-align: center;
                        border: 1px solid black;
                        vertical-align: middle;
                    }

                    /* Header background color and text alignment */
                    th {
                        background-color: #f2f2f2;
                        text-align: center;
                        font-weight: bold;
                    }

                    /* Adjust column widths */
                    th.date-col, td.date-col {
                        width: 11%; /* Adjust as needed */
                    }

                    th.name-col, td.name-col {
                        width: 13%; /* Adjust as needed */
                    }

                    th.doctor-col, td.doctor-col {
                       width: 16%; /* Adjust as needed */
                    }

                    th.staff-col, td.staff-col {
                        width: 14%; /* Adjust as needed */
                    }

                    td.instructions-col {
                        width: 35%; /* Adjust as needed */
                        text-align: left;
                        font-size: 11px;
                    }

                    /* Page break for long content */
                    .page-break {
                        page-break-before: always; /* Force page break */
                    }
                </style>

                <h5>
                    <img src="{{ url('images/Logo.png') }}" alt="Logo">
                    รายงานคำแนะนำการดูแลที่ยืนยันแล้ว
                </h5>
                <br>
                <table>
                    <thead>
                        <tr>
                            <th class="date-col">วันที่</th>
                            <th class="name-col">ชื่อผู้สูงอายุ</th>
                            <th class="doctor-col">ชื่อแพทย์</th>
                            <th class="staff-col">ชื่อเจ้าหน้าที่</th>
                            <th class="instructions-col">คำแนะนำ</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${filteredData.map((ci, index) => `
                            ${(index % 17 === 0 && index !== 0) ? `
                                <tr class="page-break">
                                    <th class="date-col">วันที่</th>
                                    <th class="name-col">ชื่อผู้สูงอายุ</th>
                                    <th class="doctor-col">ชื่อแพทย์</th>
                                    <th class="staff-col">ชื่อเจ้าหน้าที่</th>
                                    <th class="instructions-col">คำแนะนำ</th>
                                </tr>` : ''}
                            <tr>
                                <td class="date-col">${ci[0]}</td>
                                <td class="name-col">${ci[1]}</td>
                                <td class="doctor-col">${ci[4]}</td>
                                <td class="staff-col">${ci[5]}</td>
                                <td class="instructions-col">${ci[6]}</td>
                            </tr>`).join('')}
                    </tbody>
                </table>
            `;

            setTimeout(function () {
                // Configure options for generating the PDF
                var opt = {
                    margin: 0.5,
                    filename: 'รายงานคำแนะนำการดูแลที่ยืนยันแล้ว.pdf',
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

            document.querySelectorAll('.generate-single-report').forEach(button => {
                button.addEventListener('click', function() {
                    const ciId = this.dataset.id;

                    // ปรับให้การส่งค่า id ถูกต้อง
                    const url = `{{ route('report.ci.single', ':id') }}`.replace(':id', ciId);

                    fetch(url)
                        .then(response => response.text())
                        .then(data => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(data, 'text/html');
                            const element = doc.getElementById('report-content'); // ดึงเนื้อหาของรายงาน

                            // เพิ่ม CSS สำหรับจัดรูปแบบ PDF
                            const style = document.createElement('style');
                            style.innerHTML = `
                                * {
                                    font-family: 'Open Sans', Arial, sans-serif !important;
                                    color: black !important;
                                    background-color: white !important;
                                }
                            `;
                            element.appendChild(style);

                            // ตั้งค่า options สำหรับการสร้าง PDF
                            var opt = {
                                margin: 0.5,
                                image: { type: 'jpeg', quality: 0.98 },
                                html2canvas: { scale: 2 },
                                jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
                            };

                            // สร้าง PDF และเปิดในหน้าต่างใหม่
                            html2pdf().set(opt).from(element).outputPdf('blob').then(function (pdfBlob) {
                                var pdfUrl = URL.createObjectURL(pdfBlob);
                                var pdfWindow = window.open();
                                pdfWindow.location.href = pdfUrl;
                            });
                        })
                        .catch(error => console.error('Error generating PDF:', error));
                });
            });



    </script>
</html>

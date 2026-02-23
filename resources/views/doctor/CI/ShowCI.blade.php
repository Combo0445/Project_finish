<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Care Instructions</title>
    <!-- Include DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
</head>

<body >

    @include('layout.nav')

    <main class="main-content position-relative h-100 border-radius-lg">
        <div class="container-fluid py-4">

            <!-- Care Instructions Information Card -->
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4" >
                        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                            <h4>ข้อมูลคำแนะนำการดูแล</h4>
                            <button id="generate-pdf" class="btn btn-success">
                                <i class="fas fa-print"></i>
                            </button>
                        </div>
                        <div class="card-body px-3 pt-3 pb-2">
                            <div class="table-responsive p-0">
                                <table id="ciTable" class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">วันที่</th>
                                            <th class="text-center">ชื่อผู้สูงอายุ</th>
                                            <th class="text-center">ชื่อแพทย์</th>
                                            <th class="text-center">ชื่อเจ้าหน้าที่</th>
                                            <th class="text-center">คำแนะนำการดูแล</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($careInstructions as $ci)
                                            @if (empty($ci->Confirm))
                                                @php
                                                    $showRow = false;
                                                    if (Auth::user()->Type_Personnel == 'Doctor') {
                                                        if ((Auth::user()->Type_Doctor == 'กลุ่มติดสังคม' && $ci->elderly->barthel_adl->Group_ADL == 'กลุ่มติดสังคม') ||
                                                            (Auth::user()->Type_Doctor == 'กลุ่มติดบ้าน' && $ci->elderly->barthel_adl->Group_ADL == 'กลุ่มติดบ้าน') ||
                                                            (Auth::user()->Type_Doctor == 'กลุ่มติดเตียง' && $ci->elderly->barthel_adl->Group_ADL == 'กลุ่มติดเตียง')) {
                                                            $showRow = true;
                                                        }
                                                    } else {
                                                        $showRow = true;
                                                    }
                                                @endphp
                                                @if ($showRow)
                                                    <tr>
                                                        <td class="text-center">{{ $ci->Date_CI }}</td>
                                                        <td class="text-center">{{ $ci->Name_Elderly }}</td>
                                                        <td class="text-center">{{ $ci->Name_Doctor }}</td>
                                                        <td class="text-center">{{ $ci->Name_Staff }}</td>
                                                        <td class="text-center">{{ $ci->Care_instructions }}</td>
                                                        <td class="text-center">
                                                            <a href="{{ route('ci.edit', ['id' => $ci->ID_CI]) }}" class="btn btn-warning btn-sm">แก้ไข</a>
                                                            <form id="delete-ci-form-{{ $ci->ID_CI }}"
                                                                action="{{ route('ci.destroy', ['id' => $ci->ID_CI]) }}"
                                                                method="POST" style="display:inline-block;">
                                                              @csrf
                                                              @method('DELETE')
                                                              <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{ $ci->ID_CI }}')">ลบ</button>
                                                          </form>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endif
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




    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script>

        $(document).ready(function() {
            $('#ciTable').DataTable({
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

        document.getElementById('generate-pdf').addEventListener('click', function () {
            // Fetch the filtered data from DataTable
            var filteredData = $('#ciTable').DataTable().rows({ filter: 'applied' }).data().toArray();

            if (filteredData.length === 0) {
                Swal.fire('ไม่พบข้อมูลที่ตรงกับการค้นหา', '', 'error');
                return;
            }

            // Configure options for generating the PDF
            var opt = {
                margin: 0.5,
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
            };

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
                        text-align: left;
                    }

                    /* คำแนะนำการดูแล */
                    .care-instructions {
                        font-size: 11.5px;
                        text-align: left;
                        padding: 14px;
                    }

                    /* สไตล์สำหรับการขึ้นหน้าใหม่ */
                    .page-break {
                        page-break-before: always; /* บังคับขึ้นหน้าใหม่ */
                    }
                </style>

                <h5>
                    <img src="{{ url('images/Logo.png') }}" alt="Logo">
                    รายงานคำแนะนำการดูแล
                </h5>
                <br>
                <table>
                    <thead>
                        <tr>
                            <th style="width: 13%;">วันที่</th>
                            <th style="width: 17%;">ชื่อผู้สูงอายุ</th>
                            <th style="width: 17%;">ชื่อแพทย์</th>
                            <th style="width: 18%;">ชื่อเจ้าหน้าที่</th>
                            <th style="width: 40%;">คำแนะนำการดูแล</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${filteredData.map((ci, index) => `
                            ${(index % 12 === 0 && index !== 0) ? `
                                <tr class="page-break">
                                    <th style="width: 13%;">วันที่</th>
                                    <th style="width: 17%;">ชื่อผู้สูงอายุ</th>
                                    <th style="width: 17%;">ชื่อแพทย์</th>
                                    <th style="width: 18%;">ชื่อเจ้าหน้าที่</th>
                                    <th style="width: 40%;">คำแนะนำการดูแล</th>
                                </tr>` : ''}
                            <tr>
                                <td style="text-align: center;">${ci[0]}</td>
                                <td style="text-align: center;">${ci[1]}</td>
                                <td style="text-align: center;">${ci[2]}</td>
                                <td style="text-align: center;">${ci[3]}</td>
                                <td class="care-instructions">${ci[4]}</td>
                            </tr>`).join('')}
                    </tbody>
                </table>
            `;

            setTimeout(function () {
                // Generate the PDF
                html2pdf().set(opt).from(reportContent).outputPdf('blob').then(function (pdfBlob) {
                    var pdfUrl = URL.createObjectURL(pdfBlob);
                    var pdfWindow = window.open();
                    pdfWindow.location.href = pdfUrl;
                });
            });
        });

    </script>
</body>

</html>

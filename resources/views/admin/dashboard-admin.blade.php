<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage User</title>
    <!-- Include DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <!-- Include SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    {{-- pdf --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .table-responsive {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        @media (max-width: 767px) {
            .container-fluid {
                padding: 10px;
            }

            .table th,
            .table td {
                font-size: 12px;
            }

            .btn {
                width: 100%;
                margin-bottom: 5px;
            }
        }

        @media (min-width: 768px) and (max-width: 1024px) {
            .container-fluid {
                padding: 15px;
            }

            .table th,
            .table td {
                font-size: 14px;
            }

            .btn {
                width: 48%;
                margin-bottom: 10px;
            }
        }
    </style>
</head>

<body>

    @include('layout.nav')

    <main class="main-content position-relative h-100 border-radius-lg">
        <div class="container-fluid py-4">

            <!-- Search Card -->
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                            <h4>จัดการข้อมูลผู้ใช้</h4>
                            <div class="d-flex gap-2">
                                <a href="{{ route('user.register') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> สร้างบัญชี
                                </a>
                                <button id="generate-pdf" class="btn btn-success">
                                    <i class="fas fa-print"></i>
                                </button>

                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table id="myTable" class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">รูป</th>
                                            <th class="text-center">ชื่อ - นามสกุล</th>
                                            <th class="text-center">ชื่อผู้ใช้</th>
                                            <th class="text-center">ประเภทของผู้ใช้</th>
                                            <th class="text-center">ประเภทที่แพทย์ดูแล</th>
                                            <th class="text-center">อีเมล</th>
                                            <th class="text-center">ที่อยู่</th>
                                            <th class="text-center">เบอร์โทร</th>
                                            <th class="text-center">จำนวนการประเมิน ADL</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                            <tr>
                                                <td class="text-center">
                                                    <img src="{{ url('/' . $user->Image_User) }}" alt="User Image"
                                                        class="img-fluid" style="max-width: 50px;">
                                                </td>
                                                <td class="text-center">{{ $user->Name_User ?: 'ไม่มีข้อมูล' }}</td>
                                                <td class="text-center">{{ $user->Username ?: 'ไม่มีข้อมูล' }}</td>
                                                <td class="text-center">
                                                    @if ($user->Type_Personnel === 'Admin')
                                                        แอดมิน
                                                    @elseif ($user->Type_Personnel === 'Staff')
                                                        เจ้าหน้าที่
                                                    @elseif ($user->Type_Personnel === 'Doctor')
                                                        แพทย์
                                                    @else
                                                        {{ $user->Type_Personnel }}
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ $user->Type_Doctor }}</td>
                                                <td class="text-center">{{ $user->Email ?: 'ไม่มีข้อมูล' }}</td>
                                                <td class="text-center">{{ $user->Address ?: 'ไม่มีข้อมูล' }}</td>
                                                <td class="text-center">{{ $user->Phone ?: 'ไม่มีข้อมูล' }}</td>
                                                <td class="text-center">
                                                    <span class="badge bg-secondary">{{ $user->barthel_adls_count }}</span>
                                                </td>
                                                <td class="text-center">
                                                    @if ($user->Type_Personnel !== 'Admin')
                                                        <button onclick="confirmDelete('{{ $user->ID_User }}')"
                                                            class="btn btn-danger btn-sm">ลบ</button>
                                                        <!-- Hidden form to submit DELETE request -->
                                                        <form id="delete-form-{{ $user->ID_User }}"
                                                            action="{{ route('user.delete', $user->ID_User) }}" method="POST"
                                                            style="display:none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    @endif
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

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Include DataTables JS -->
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <!-- Include SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
        document.getElementById('generate-pdf').addEventListener('click', function () {
            // Fetch the filtered data from DataTable
            var filteredData = $('#myTable').DataTable().rows({ filter: 'applied' }).data().toArray();

            if (filteredData.length === 0) {
                Swal.fire('ไม่พบข้อมูลที่ตรงกับการค้นหา', '', 'error');
                return;
            }

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
                    /* สไตล์สำหรับ h5 */
                    h5 {
                        font-size: 20px;
                    }

                    /* สไตล์สำหรับรูปภาพ */
                    img {
                        height: 80px;
                        vertical-align: middle;
                    }

                    /* สไตล์สำหรับตาราง */
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-bottom: 20px;
                        font-size: 14px;
                    }

                    th, td {
                        font-size: 12.5px;
                        padding: 8px;
                        text-align: center;
                        border: 1px solid black;
                    }

                    .address {
                        font-size: 12.5px;
                        text-align: left;
                    }

                    /* สไตล์สำหรับรูปในตาราง */
                    .user-image {
                        max-width: 40px;
                        height: auto;
                    }

                    /* สไตล์สำหรับการขึ้นหน้าใหม่ */
                    .page-break {
                        page-break-before: always; /* บังคับขึ้นหน้าใหม่ */
                    }
                </style>

                <h5>
                    <img src="{{ url('images/Logo.png') }}" alt="Logo">
                    รายงานข้อมูลผู้ใช้
                </h5>
                <br>
                <table>
                    <thead>
                        <tr>
                            <th style="width: 10%;">รูป</th>
                            <th style="width: 20%;">ชื่อ - นามสกุล</th>
                            <th style="width: 15%;">ประเภทผู้ใช้</th>
                            <th style="width: 40%;" class="address">ที่อยู่</th>
                            <th style="width: 15%;">เบอร์โทร</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${filteredData.map((user, index) => `
                            ${(index % 11 === 0 && index !== 0) ? `
                                <tr class="page-break">
                                    <th style="width: 10%;">รูป</th>
                                    <th style="width: 20%;">ชื่อ - นามสกุล</th>
                                    <th style="width: 15%;">ประเภทผู้ใช้</th>
                                    <th style="width: 40%;" class="address">ที่อยู่</th>
                                    <th style="width: 15%;">เบอร์โทร</th>
                                </tr>` : ''}
                            <tr>
                                <td>${user[0]}</td>
                                <td>${user[1]}</td>
                                <td>${user[3]}</td>
                                <td class="address">${user[6]}</td>
                                <td>${user[7]}</td>
                            </tr>`).join('')}
                    </tbody>
                </table>
            `;

            setTimeout(function () {
                html2pdf().set(opt).from(reportContent).outputPdf('blob').then(function (pdfBlob) {
                    var pdfUrl = URL.createObjectURL(pdfBlob); // สร้าง URL จาก Blob
                    var pdfWindow = window.open(); // เปิดหน้าแท็บใหม่
                    pdfWindow.location.href = pdfUrl; // ตั้งค่า URL ของ PDF
                });
            });
        });

            });
        });


        function confirmDelete(userId) {
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: "คุณจะไม่สามารถย้อนกลับได้หลังจากการลบ!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    // ส่งฟอร์มลบ
                    document.getElementById('delete-form-' + userId).submit();
                }
            });
        }

        $(document).ready(function () {
            $('#myTable').DataTable({
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
    </script>
</body>

</html>
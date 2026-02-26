<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>

<script>
    document.getElementById('generate-pdf')?.addEventListener('click', function () {
        var filteredData = $('#userTable').DataTable().rows({ filter: 'applied' }).data().toArray();

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

        var reportContent = document.createElement('div');
        reportContent.innerHTML = `
            <style>
                * {
                        font-family: 'Open Sans', Arial, sans-serif !important;
                        color: black !important;
                        background-color: white !important;
                    }
                h5 { font-size: 20px; }
                img { height: 80px; vertical-align: middle; }
                table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 14px; }
                th, td { font-size: 12.5px; padding: 8px; text-align: center; border: 1px solid black; }
                .address { font-size: 12.5px; text-align: left; }
                .user-image { max-width: 40px; height: auto; }
                .page-break { page-break-before: always; }
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
                    \${filteredData.map((user, index) => \`
                        \${(index % 11 === 0 && index !== 0) ? \`
                            <tr class="page-break">
                                <th style="width: 10%;">รูป</th>
                                <th style="width: 20%;">ชื่อ - นามสกุล</th>
                                <th style="width: 15%;">ประเภทผู้ใช้</th>
                                <th style="width: 40%;" class="address">ที่อยู่</th>
                                <th style="width: 15%;">เบอร์โทร</th>
                            </tr>\` : ''}
                        <tr>
                            <td>\${user[0]}</td>
                            <td>\${user[1]}</td>
                            <td>\${user[3]}</td>
                            <td class="address">\${user[5]}</td>
                            <td>\${user[6]}</td>
                        </tr>\`).join('')}
                </tbody>
            </table>
        `;

        setTimeout(function () {
            html2pdf().set(opt).from(reportContent).outputPdf('blob').then(function (pdfBlob) {
                var pdfUrl = URL.createObjectURL(pdfBlob);
                var pdfWindow = window.open();
                pdfWindow.location.href = pdfUrl;
            });
        });
    });
</script>
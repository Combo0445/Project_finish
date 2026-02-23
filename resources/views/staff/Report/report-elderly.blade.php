<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานข้อมูลผู้สูงอายุ</title>
    <link href="{{ url('assets/css/argon-dashboard.css') }}" rel="stylesheet"/>
    <link href="{{ url('assets/css/nucleo-icons.css') }}" rel="stylesheet"/>
    <link href="{{ url('assets/css/nucleo-svg.css') }}" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            font-family: 'Open Sans', Arial, sans-serif !important;
            color: black !important;
            background-color: white !important;
        }

        body {
            width: 210mm;
            height: 297mm;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .container {
            padding: 10mm;
        }

        h5 {
            padding: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10mm;
        }

        th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 5px;
            text-align: left;
        }

        /* สำหรับการบังคับแบ่งหน้า */
        @media print {
            .page-break {
                display: block;
                page-break-before: always;
                padding-top: 20mm; /* ปรับระยะห่างจากหัวกระดาษ */
            }

            .charts{
                page-break-before: always;
                padding-top: 20mm;
            }
        }

        .charts {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .chart-container {
            width: 45%;
            height: 300px;
            margin-bottom: 10mm;
        }

        img.logo {
            width: 80px;  /* ปรับความกว้าง */
            height: auto; /* ให้คงสัดส่วนเดิม */
            margin-right: 10px;
        }
    </style>

</head>
<body>

    <div class="container">
        <h5>
            <img src="{{ url('images/Logo.png') }}" alt="Logo" class="logo">
            รายงานข้อมูลผู้สูงอายุ
        </h5>
        <table>
            <thead>

            </thead>
            <tbody id="table-body">
                <tr>
                    <th>รูป</th>
                    <th>ชื่อ</th>
                    <th>ที่อยู่</th>
                    <th>เบอร์โทร</th>
                </tr>
                @foreach($elderlies as $index => $elderly)
                @if($index % 12 == 0 && $index != 0)
                <tr class="page-break">
                </tr>
                <tr>
                    <th>รูป</th>
                    <th>ชื่อ</th>
                    <th>ที่อยู่</th>
                    <th>เบอร์โทร</th>
                </tr>
                @endif
                <tr>
                    <td>
                        @if($elderly->Image_Elderly)
                            <img src="{{ url('storage/'.$elderly->Image_Elderly) }}" alt="Elderly Image" width="50">
                        @else
                            <img src="{{ url('storage/default.png') }}" alt="Elderly Image" width="50">
                        @endif
                    </td>
                    <td>{{ $elderly->Name_Elderly }}</td>
                    <td>{{ $elderly->Address }}</td>
                    <td>{{ $elderly->Phone_Elderly }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>


        <div class="charts">
            <div class="chart-container">
                <canvas id="ageBarChart"></canvas>
            </div>
            <div class="chart-container">
                <canvas id="agePieChart"></canvas>
            </div>
            <div class="chart-container">
                <canvas id="adlBarChart"></canvas>
            </div>
            <div class="chart-container">
                <canvas id="adlPieChart"></canvas>
            </div>
        </div>
    </div>

    <script>


            // Charts setup
            var ageGroups = @json($ageGroups);
            var adlGroups = @json($adlGroups);

            var ageBarChartCtx = document.getElementById('ageBarChart').getContext('2d');
            new Chart(ageBarChartCtx, {
                type: 'bar',
                data: {
                    labels: Object.keys(ageGroups),
                    datasets: [{
                        label: 'จำนวนผู้สูงอายุ',
                        data: Object.values(ageGroups),
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            display: false
                        }
                    }
                }
            });

            var agePieChartCtx = document.getElementById('agePieChart').getContext('2d');
            new Chart(agePieChartCtx, {
                type: 'pie',
                data: {
                    labels: Object.keys(ageGroups),
                    datasets: [{
                        label: 'สัดส่วนผู้สูงอายุ',
                        data: Object.values(ageGroups),
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                    }
                }
            });

            var adlBarChartCtx = document.getElementById('adlBarChart').getContext('2d');
            new Chart(adlBarChartCtx, {
                type: 'bar',
                data: {
                    labels: Object.keys(adlGroups),
                    datasets: [{
                        label: 'จำนวนผู้สูงอายุตามกลุ่ม ADL',
                        data: Object.values(adlGroups),
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y',
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            var adlDoughnutChartCtx = document.getElementById('adlPieChart').getContext('2d');
            new Chart(adlDoughnutChartCtx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(adlGroups),
                    datasets: [{
                        label: 'สัดส่วนผู้สูงอายุตามกลุ่ม ADL',
                        data: Object.values(adlGroups),
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                    }
                }
            });

            window.onafterprint = function() {
                window.history.back();
            };

            setTimeout(function() {
                window.print();
            }, 1000);

    </script>
</body>
</html>

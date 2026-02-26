<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบประเมินความสามารถในการดำเนินกิจวัตรประจำวันของผู้สูงอายุ</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="{{ url('assets/css/argon-dashboard.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&family=Open+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Override Argon Dashboard CSS variable to use Sarabun for Thai */
        :root {
            --bs-font-sans-serif: 'Sarabun', 'Open Sans', sans-serif;
        }

        body {
            font-family: 'Sarabun', 'Open Sans', sans-serif !important;
            background-color: #f8f9fa;
            color: #344767;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            padding-top: 80px;
            /* Space for fixed navbar */
        }

        .content {
            flex: 1;
            /* ให้เนื้อหายืดเต็มพื้นที่ที่เหลือ */
        }

        .news,
        .statistics,
        .contact-form {
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .news h2,
        .statistics h2,
        .contact-form h2 {
            margin-bottom: 20px;
        }

        .news-item,
        .stat-item {
            margin-bottom: 15px;
        }

        .news-item h3,
        .stat-item h3 {
            margin-bottom: 5px;
        }

        .news-item p {
            margin-bottom: 10px;
        }

        .news-item a {
            color: #355e3b;
            text-decoration: none;
        }

        .news-item a:hover {
            text-decoration: underline;
        }

        footer {
            background-color: #344767;
            color: #fff;
            text-align: center;
            padding: 10px 0;
        }

        footer ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        footer ul li {
            display: inline;
        }

        footer ul li a {
            color: #fff;
            text-decoration: none;
        }

        footer ul li a:hover {
            text-decoration: underline;
        }

        .slider {
            position: relative;
            overflow: hidden;
            width: 100%;
            height: 500px;
            margin-bottom: 50px;
        }

        .slides {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }

        .slides img {
            width: 100%;
            height: 500px;
            object-fit: cover;
            flex-shrink: 0;
        }

        .prev,
        .next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0, 0, 0, 0.5);
            color: #fff;
            border: none;
            padding: 10px;
            cursor: pointer;
            z-index: 1;
        }

        .prev {
            left: 10px;
        }

        .next {
            right: 10px;
        }

        .dashboard-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .dashboard-card .icon {
            font-size: 2em;
            color: #66a370;
        }

        .dashboard-card h3 {
            margin: 0;
        }

        .dashboard-card p {
            margin: 5px 0 0;
        }

        .news-item img {
            cursor: pointer;
            transition: transform 0.2s;
        }

        .news-container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 10px;
            margin-bottom: 15px;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .news-item {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        #adlChart {
            max-width: 100%;
            margin: 20px 0;
        }

        .office-info {
            position: relative;
            background-image: url('{{ url('images/ooff.jpg') }}');
            background-size: cover;
            background-position: center;
            padding: 100px 0;
            color: white;
            text-align: center;
            margin-bottom: 0;
        }

        .office-info .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.8);
            z-index: 1;
        }

        .office-info .container {
            position: relative;
            z-index: 2;
        }

        .info-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .office-logo {
            width: 150px;
            /* ปรับขนาดให้ใหญ่ขึ้น */
            height: auto;
            /* รักษาอัตราส่วนภาพ */
            margin-bottom: 20px;
        }


        .office-info h4 {
            margin-bottom: 10px;
        }

        .office-info p {
            font-size: 1.2rem;
        }

        @media (min-width: 768px) {
            .office-info h4 {
                font-size: 2.5rem;
            }

            .office-info p {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    @include('layout.nav')

    <div class="content">
        <!-- Slider -->
        <section class="slider">
            <div class="slides">
                @foreach ($sliders as $slider)
                    <img src="{{ url('storage/' . $slider->image) }}" alt="Slider Image">
                @endforeach
            </div>
            <button class="prev" onclick="plusSlides(-1)">&#10094;</button>
            <button class="next" onclick="plusSlides(1)">&#10095;</button>
        </section>

        <div class="container-fluid">
            <div class="row">
                <!-- ข่าวสารประชาสัมพันธ์ -->
                <div class="col-sm-9">
                    <section class="news">
                        <h3 style="text-align: center;">ข่าวสารประชาสัมพันธ์</h3>
                    </section>
                    <div class="row">
                        @foreach ($news as $newsItem)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="news-container">
                                    <div class="news-item">
                                        <a href="{{ route('news.show', ['id' => $newsItem->id]) }}">
                                            <img src="{{ $newsItem->images->first() ? url('storage/' . $newsItem->images->first()->image_path) : url('path/to/default/image.jpg') }}"
                                                alt="ไม่มีรูปภาพ" class="card-img-top"
                                                style="height: 180px; object-fit: cover;">
                                        </a>
                                        <h5 class="mt-2">
                                            <a
                                                href="{{ route('news.show', ['id' => $newsItem->id]) }}">{{ $newsItem->title }}</a>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- จำนวนการประเมิน ADL -->
                <div class="col-sm-3">
                    <div class="dashboard-card">
                        <div>
                            <h4>การประเมินความสามารถในการดำเนินกิจวัตรประจำวันของผู้สูงอายุ</h4>
                        </div>
                        <div class="icon">
                            <i class="ni ni-check-bold"></i>
                        </div>
                    </div>
                    <div class="dashboard-card">
                        <canvas id="adlChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Office Info Section -->
        <section class="office-info">
            <div class="overlay"></div>
            <div class="container">
                <div class="info-content">
                    <img src="{{ url('images/logo.png') }}" alt="Logo" class="office-logo">
                    <h4 style="color: #1BAE70;">สำนักงานสาธารณสุข อำเภอห้วยราช</h4>
                </div>
                <p>สำนักงานสาธารณสุขอำเภอห้วยราช หมู่ที่ 3 ถนนสุขาภิบาล</p>
                <p>ตำบลห้วยราชอำเภอห้วยราช จังหวัดบุรีรัมย์ 31000 </p>
                <p>โทร 044 606 033 - 43</p>
            </div>
        </section>



        <!-- Footer -->
        <footer>
            <p>&copy; 2024 สำนักงานสาธารณสุข อำเภอห้วยราช จังหวัดบุรีรัมย์</p>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

        <script>
            let slideIndex = 0;
            const slides = document.querySelector('.slides');

            function showSlides() {
                const totalSlides = slides.children.length;
                slideIndex++;
                if (slideIndex >= totalSlides) {
                    slideIndex = 0;
                }
                slides.style.transform = `translateX(${-slideIndex * 100}%)`;
            }

            function plusSlides(n) {
                slideIndex += n;
                const totalSlides = slides.children.length;
                if (slideIndex < 0) {
                    slideIndex = totalSlides - 1;
                } else if (slideIndex >= totalSlides) {
                    slideIndex = 0;
                }
                slides.style.transform = `translateX(${-slideIndex * 100}%)`;
            }

            setInterval(showSlides, 3000);

            const adlChartContext = document.getElementById('adlChart').getContext('2d');
            const adlData = {
                labels: ['กลุ่มติดสังคม', 'กลุ่มติดบ้าน', 'กลุ่มติดเตียง'],
                datasets: [{
                    label: 'จำนวนการประเมิน ADL',
                    data: [
            {{ $adlGroupCounts['กลุ่มติดสังคม'] }},
            {{ $adlGroupCounts['กลุ่มติดบ้าน'] }},
                        {{ $adlGroupCounts['กลุ่มติดเตียง'] }}
                    ],
                    backgroundColor: ['#3498db', '#2ecc71', '#e74c3c'],
                    borderWidth: 1
                }]
            };

            const adlChart = new Chart(adlChartContext, {
                type: 'doughnut',
                data: adlData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            enabled: true,
                        },
                        datalabels: {
                            formatter: (value, ctx) => {
                                let sum = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                let percentage = (value * 100 / sum).toFixed(2) + "%";
                                return percentage;
                            },
                            color: '#fff',  // สีข้อความที่แสดง
                            font: {
                                weight: 'bold',
                                size: 14
                            }
                        }
                    },
                    cutout: '50%',
                },
                plugins: [ChartDataLabels]  // เพิ่ม plugin datalabels เข้าไปในกราฟ
            });

        </script>
</body>

</html>
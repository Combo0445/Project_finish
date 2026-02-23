<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-Compatible" content="ie=edge">
    <title>Contact Us</title>
    <!-- Link to Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .contact-section {
            padding: 50px 0;
        }
        .contact-info {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .navbar {
            background-color: #0062cc;
        }
        .navbar-brand, .nav-link {
            color: white !important;
        }
        .navbar-brand:hover, .nav-link:hover {
            color: #f8f9fa !important;
        }
        /* Adjust phone number spacing */
        .phone-numbers p {
            line-height: 1.5;
            margin-bottom: 0;
        }
        /* Flexbox to align phone and email side by side */
        .contact-details {
            display: flex;
            justify-content: space-between;
        }
        .contact-details .phone-numbers, .contact-details .email {
            flex: 1;
            padding: 10px;
        }
        /* Center the headings */
        .bbbb {
            color: #67748e;
            text-align: center;
        }
        h5{
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- Include Navigation -->
    @include('layout.nav')

    <!-- Contact Section -->
    <section class="contact-section">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="aaaa">ติดต่อเรา</h3>

                    <div class="contact-info">
                        <h5>ที่อยู่</h5>
                        <div class="phone-numbers">
                            <p>สำนักงานสาธารณสุขอำเภอห้วยราช หมู่ที่ 3 ถนนสุขาภิบาล ตำบลห้วยราช</p>
                            <p>อำเภอห้วยราช จังหวัดบุรีรัมย์ 31000</p>
                            <p>โทร 044 606 033 - 43</p>
                            <hr>
                        </div>

                        <!-- Flexbox to align phone and email -->
                        <div class="contact-details">
                            <div class="phone-numbers">
                                <h5>เบอร์โทรศัพท์</h5>
                                <p class="bbbb">044 606 033</p>
                                <p class="bbbb">044 606 034</p>
                                <p class="bbbb">044 606 035</p>
                                <p class="bbbb">044 606 036</p>
                                <p class="bbbb">044 606 037</p>
                                <p class="bbbb">044 606 038</p>
                                <p class="bbbb">044 606 039</p>
                                <p class="bbbb">044 606 040</p>
                                <p class="bbbb">044 606 041</p>
                                <p class="bbbb">044 606 042</p>
                                <p class="bbbb">044 606 043</p>
                            </div>
                            <div class="email">
                                <h5>อีเมล (Email)</h5>
                                <p class="bbbb">huairat.health@hospital.com</p>
                                <br>
                                <h5>เฟซบุ๊ก (Facebook)</h5>
                                <p class="bbbb">
                                    <a href="https://www.facebook.com/people/%E0%B8%AA%E0%B8%B3%E0%B8%99%E0%B8%B1%E0%B8%81%E0%B8%87%E0%B8%B2%E0%B8%99%E0%B8%AA%E0%B8%B2%E0%B8%98%E0%B8%B2%E0%B8%A3%E0%B8%93%E0%B8%AA%E0%B8%B8%E0%B8%82-%E0%B8%AD%E0%B8%B3%E0%B9%80%E0%B8%A0%E0%B8%AD%E0%B8%AB%E0%B9%89%E0%B8%A7%E0%B8%A2%E0%B8%A3%E0%B8%B2%E0%B8%8A/100003197671366" target="_blank" class="bbbb">สำนักงานสาธารณสุข อำเภอห้วยราช</a>
                                </p>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <h3>ตำแหน่งที่ตั้งของเรา</h3>
                    <!-- Embed Google Map -->
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d240.8995393443442!2d103.19263725505863!3d14.97089444599563!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3119eb20e8008e73%3A0x1bd0c25a9c4865c8!2z4Liq4Liz4LiZ4Lix4LiB4LiH4Liy4LiZ4Liq4Liy4LiY4Liy4Lij4LiT4Liq4Li44LiC4Lit4Liz4LmA4Lig4Lit4Lir4LmJ4Lin4Lii4Lij4Liy4LiK!5e0!3m2!1sth!2sth!4v1725688867880!5m2!1sth!2sth" width="800" height="600" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    @include('layout.footer')
</body>
</html>

@extends('layouts.app')

@section('title', 'Contact Us')

@push('styles')
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
@endpush

@section('content')
    <!-- Contact Section -->
    <section class="contact-section">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="aaaa">ติดต่อเรา</h3>

                    <div class="contact-info">
                        <h5>ที่อยู่</h5>
                        <div class="phone-numbers">
                            <p>สำนักงานสาธารณสุขตัวอย่าง (Demo) ตำบลตัวอย่าง</p>
                            <p>อำเภอตัวอย่าง จังหวัดตัวอย่าง 10000</p>
                            <p>โทร 080-000-0000 (ตัวอย่าง)</p>
                            <hr>
                        </div>

                        <!-- Flexbox to align phone and email -->
                        <div class="contact-details">
                            <div class="phone-numbers">
                                <h5>เบอร์โทรศัพท์</h5>
                                <p class="bbbb">080-000-0001 (ตัวอย่าง)</p>
                                <p class="bbbb">080-000-0002 (ตัวอย่าง)</p>
                            </div>
                            <div class="email">
                                <h5>อีเมล (Email)</h5>
                                <p class="bbbb">demo@example.com</p>
                                <br>
                                <h5>เฟซบุ๊ก (Facebook)</h5>
                                <p class="bbbb">
                                    <span class="bbbb">สำนักงานสาธารณสุขตัวอย่าง (Demo)</span>
                                </p>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <h3>ตำแหน่งที่ตั้งของเรา</h3>
                    <!-- Embed Google Map (generic placeholder — no real office location) -->
                    <iframe src="https://www.google.com/maps?q=Thailand&z=6&output=embed" width="800" height="600" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </section>
@endsection

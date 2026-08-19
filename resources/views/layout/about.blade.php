@extends('layouts.app')

@section('title', 'เกี่ยวกับเรา')

@push('styles')
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .about-section {
            padding: 50px 0;
            background-color: #fff;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .about-section h3 {
            text-align: center;
            margin-bottom: 40px;
        }
        .about-content {
            margin: 0 auto;
            max-width: 800px;
            font-size: 18px;
            line-height: 1.6;
        }

        .navbar-brand, .nav-link {
            color: white !important;
        }
        .navbar-brand:hover, .nav-link:hover {
            color: #f8f9fa !important;
        }
    </style>
@endpush

@section('content')
    <!-- About Section -->
    <section class="about-section">
        <div class="container">
            <h3>เกี่ยวกับ</h3>
            <div class="about-content">
                <p>ระบบประเมินผู้สูงอายุที่มีภาวะพึ่งพิง (ADL) เป็นโครงการตัวอย่าง (Demo) ที่พัฒนาขึ้นเพื่อสาธิตการติดตามและประเมินความสามารถในการทำกิจวัตรประจำวันของผู้สูงอายุที่มีภาวะพึ่งพิง โดยใช้ดัชนี Barthel ADL ซึ่งเป็นดัชนีที่ใช้กันอย่างแพร่หลายในการประเมินภาวะพึ่งพิงของผู้สูงอายุ</p>

                <p>ระบบนี้มีเป้าหมายเพื่อสนับสนุนบุคลากรทางการแพทย์และผู้ดูแลผู้สูงอายุ ในการวางแผนการรักษาและการฟื้นฟูสมรรถภาพ เพื่อให้ผู้สูงอายุสามารถกลับมาดำเนินชีวิตประจำวันได้อย่างมีคุณภาพและสมดุล ระบบนี้มีการจัดการข้อมูลที่แม่นยำ รวดเร็ว และมีความปลอดภัยสูง อีกทั้งยังช่วยลดภาระงานเอกสารและเพิ่มประสิทธิภาพในการทำงานของเจ้าหน้าที่</p>

                <p>โครงการนี้มีความมุ่งมั่นในการพัฒนาระบบที่ทันสมัย และตอบสนองต่อความต้องการของสังคมที่มีผู้สูงอายุเพิ่มขึ้นอย่างต่อเนื่อง ด้วยเป้าหมายในการส่งเสริมคุณภาพชีวิตของผู้สูงอายุในชุมชนอย่างยั่งยืน</p>
            </div>
        </div>
    </section>
@endsection

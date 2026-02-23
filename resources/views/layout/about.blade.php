<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>เกี่ยวกับเรา</title>
    <!-- Link to Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
</head>
<body>

    <!-- Include Navigation -->
    @include('layout.nav')

    <!-- About Section -->
    <section class="about-section">
        <div class="container">
            <h3>เกี่ยวกับ</h3>
            <div class="about-content">
                <p>ระบบประเมินความสามารถในการดำเนินกิจวัตรประจำวันของผู้สูงอายุที่มีภาวะพึ่งพิง เป็นโครงการที่พัฒนาขึ้นโดยสำนักงานสาธารณสุขอำเภอห้วยราช จังหวัดบุรีรัมย์ เพื่อติดตามและประเมินความสามารถในการทำกิจวัตรประจำวันของผู้สูงอายุที่มีภาวะพึ่งพิง โดยใช้ดัชนี Barthel ADL ซึ่งเป็นดัชนีที่ใช้กันอย่างแพร่หลายในการประเมินภาวะพึ่งพิงของผู้สูงอายุ</p>

                <p>ระบบนี้มีเป้าหมายเพื่อสนับสนุนบุคลากรทางการแพทย์และผู้ดูแลผู้สูงอายุ ในการวางแผนการรักษาและการฟื้นฟูสมรรถภาพ เพื่อให้ผู้สูงอายุสามารถกลับมาดำเนินชีวิตประจำวันได้อย่างมีคุณภาพและสมดุล ระบบนี้มีการจัดการข้อมูลที่แม่นยำ รวดเร็ว และมีความปลอดภัยสูง อีกทั้งยังช่วยลดภาระงานเอกสารและเพิ่มประสิทธิภาพในการทำงานของเจ้าหน้าที่</p>

                <p>สำนักงานสาธารณสุขอำเภอห้วยราชมีความมุ่งมั่นในการพัฒนาระบบที่ทันสมัย และตอบสนองต่อความต้องการของสังคมที่มีผู้สูงอายุเพิ่มขึ้นอย่างต่อเนื่อง ด้วยเป้าหมายในการส่งเสริมคุณภาพชีวิตของผู้สูงอายุในชุมชนอย่างยั่งยืน</p>
            </div>
        </div>
    </section>
    @include('layout.footer')
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>วิสัยทัศน์/พันธกิจ</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: #fff;
            padding: 60px;

            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h3 {

            text-align: center;
        }

        p {
            margin: 20px 0;

        }
        .vision {
            padding-left: 100px; /* ขยับเนื้อหาไปทางขวา */
        }
    </style>
</head>
<body>

    @include('layout.nav')

    <div class="container">
        <h3>วิสัยทัศน์ / พันธกิจ</h3>

        <h4 class="vision">วิสัยทัศน์</h4>
        <p class="vision">
            มุ่งมั่นที่จะเป็นผู้นำในด้านการให้บริการสุขภาพที่มีคุณภาพและเท่าเทียม สร้างความเป็นอยู่ที่ดีของประชาชนในอำเภอห้วยราช ผ่านการส่งเสริมสุขภาพ ป้องกันโรค และการฟื้นฟูสุขภาพด้วยมาตรฐานที่สูงสุด
        </p>

        <h4 class="vision">พันธกิจ</h4>
        <ul class="vision">
            <li>ให้บริการทางการแพทย์และสาธารณสุขที่มีคุณภาพและครอบคลุมทุกกลุ่มประชากรในอำเภอห้วยราช</li>
            <li>ส่งเสริมการป้องกันและควบคุมโรคในชุมชนผ่านการสร้างความรู้และตระหนักถึงสุขภาพที่ดี</li>
            <li>พัฒนาศักยภาพของบุคลากรทางการแพทย์และสาธารณสุขให้มีความเชี่ยวชาญและทันสมัย</li>
            <li>ส่งเสริมการมีส่วนร่วมของชุมชนในการดูแลสุขภาพของตนเองและครอบครัว</li>
            <li>พัฒนาระบบการบริหารจัดการที่มีประสิทธิภาพและทันสมัยเพื่อรองรับการให้บริการที่ครอบคลุมและยั่งยืน</li>
        </ul>
    </div>
    @include('layout.footer')
</body>
</html>

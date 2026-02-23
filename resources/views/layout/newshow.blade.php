<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $newsItem->title }}</title>
    <link href="{{ url('assets/css/argon-dashboard.css') }}" rel="stylesheet" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f8f9fa;
            color: #344767;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .news-header {
            text-align: center;
            padding: 70px;
            margin-bottom: 0px;
            color: white;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background-color: white;
            padding: 0px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 20px;
            flex: 1; /* ทำให้เนื้อหาใช้พื้นที่ที่เหลืออยู่ */
        }

        .news-content {
            margin-bottom: 20px;
        }

        .image-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .image-grid img {
            width: 100%;
            height: auto;
            object-fit: cover;
            border-radius: 10px;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
        }

        footer {
            background-color: #344767;
            color: white;
            text-align: center;
            padding: 10px;
            margin-top: auto; /* ทำให้ footer ติดข้างล่าง */
        }

        footer a {
            color: white;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    @include('layout.nav')

    <!-- News Header -->
    <section class="news-header">
        <h3>{{ $newsItem->title }}</h3>
    </section>

    <!-- News Content -->
    <div class="container">
        <div class="news-content">
            <div id="editor-container">
                {!! $newsItem->content !!}
            </div>
        </div>

        <!-- Image Grid -->
        <div class="image-grid">
            @foreach ($newsItem->images as $image)
                <div class="image-item">
                    <img src="{{ url('storage/' . $image->image_path) }}" alt="{{ $newsItem->title }}">
                </div>
            @endforeach
        </div>

        <!-- Back to Home Link -->
        <a href="{{ url('/') }}" class="back-link">← กลับไปหน้าหลัก</a>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 สำนักงานสาธารณสุข อำเภอห้วยราช จังหวัดบุรีรัมย์</p>
    </footer>

</body>

    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        var quill = new Quill('#editor-container', {

            readOnly: true,  // Set to true to make it display-only
        });
    </script>

</html>

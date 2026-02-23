<!DOCTYPE html>
<html lang="en">

<!-- 
    Admin Layout: Manages the administrative dashboard layout, 
    including news management modals and slider controls.
-->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการข่าวสาร</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Quill CSS -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <!-- Quill JS -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f8f9fa;
            color: #344767;
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

        .statistics .stats {
            display: flex;
            gap: 20px;
        }

        .statistics .stat-item {
            flex: 1;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .contact-form form div {
            margin-bottom: 15px;
        }

        .contact-form label {
            display: block;
            margin-bottom: 5px;
        }

        .contact-form input,
        .contact-form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 5px;
        }

        .contact-form button {
            background-color: #fb6340;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .contact-form button:hover {
            background-color: #ea3005;
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

        .modal img {
            width: 100%;
            height: auto;
        }

        .modal-dialog-centered {
            max-width: 90%;
            margin: 30px auto;
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
            color: #fb6340;
        }

        .dashboard-card h3 {
            margin: 0;
        }

        .dashboard-card p {
            margin: 5px 0 0;
        }

        .news-container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 10px;
            margin-bottom: 50px;
            /* ลด margin ลงเพื่อให้ card ชิดกันมากขึ้น */
            height: 70%;
            /* ให้ card เต็มความสูงของคอลัมน์ */
            display: flex;
            flex-direction: column;
            /* จัดเลย์เอาต์ให้เป็นแบบ column เพื่อให้เนื้อหายืด */
        }

        .admin-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-bottom: 20px;
        }

        .admin-buttons .btn {
            margin-right: 0;
        }

        .admin-buttons a {
            background-color: #fb6340;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .admin-buttons a:hover {
            background-color: #ea3005;
        }

        .image-grid {
            display: grid;
            gap: 10px;
        }

        .image-grid img {
            width: 100%;
            height: auto;
            object-fit: cover;
        }

        .image-item::after {
            content: "";
            display: block;
            padding-bottom: 100%;
            /* Maintain square aspect ratio */
        }

        .image-item img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-grid {
            display: grid;
            gap: 10px;
        }

        .image-grid img {
            width: 100%;
            height: auto;
            object-fit: cover;
        }

        @media (min-width: 600px) {
            .image-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 900px) {
            .image-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .image-item {
            position: relative;
            overflow: hidden;
        }

        .image-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .more-overlay .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            font-size: 2rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .short-content {
            display: block;
        }

        .full-content {
            display: none;
        }

        .custom-modal-size {
            max-width: 90%;
            /* ปรับตามที่ต้องการ */
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    @include('layout.nav')

    <!-- Slider Management Section -->
    <section class="slider">
        <div class="slides">
            @foreach ($sliders as $slider)
                <img src="{{ url('storage/' . $slider->image) }}" alt="Slider Image">
            @endforeach
        </div>
        <button class="prev" onclick="plusSlides(-1)">&#10094;</button>
        <button class="next" onclick="plusSlides(1)">&#10095;</button>
    </section>
    <div class="admin-buttons">
        <button class="btn btn-primary" data-toggle="modal"
            data-target="#createSliderModal">เพิ่มรูปเลื่อนสไลด์</button>
        <button class="btn btn-primary" data-toggle="modal" data-target="#viewSliderModal">แก้ไขรูปเลื่อนสไลด์</button>
    </div>

    <div class="container-fluid">
        <div class="row">
            <!-- ข่าวสารประชาสัมพันธ์ (Admin News Management) -->
            <div class="col-sm-9">
                <section class="news">
                    <h3 style="text-align: center;">ข่าวสารประชาสัมพันธ์</h3>
                </section>
                <div class="admin-buttons">
                    <button class="btn btn-primary" data-toggle="modal"
                        data-target="#createNewsModal">เพิ่มข่าวสาร</button>
                </div>
                <div class="row">
                    @foreach ($news as $newsItem)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100">
                                <img src="{{ $newsItem->images->first() ? url('storage/' . $newsItem->images->first()->image_path) : url('path/to/default/image.jpg') }}"
                                    alt="ไม่มีรูปภาพ" class="card-img-top" style="height: 180px; object-fit: cover;">

                                <div class="card-body">
                                    <h5 class="card-title">{{ $newsItem->title }}</h5>
                                </div>
                                <div class="card-footer d-flex justify-content-end">
                                    <button class="btn btn-warning btn-sm" style="margin-right: 10px;"
                                        onclick="showEditModal('{{ $newsItem->id }}', '{{ $newsItem->title }}', '{{ $newsItem->content }}', [@foreach ($newsItem->images as $image) '{{ url('storage/' . $image->image_path) }}', @endforeach])">แก้ไข</button>
                                    <form action="{{ route('admin.news.destroy', $newsItem->id) }}" method="POST"
                                        id="delete-news-form-{{ $newsItem->id }}" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm"
                                            onclick="confirmDeleteNews('{{ $newsItem->id }}')">ลบ</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <!-- จำนวนการประเมิน ADL และ CG -->
            <div class="col-sm-3">
                <div class="dashboard-card">
                    <div>
                        <h3>การประเมินความสามารถในการดำเนินกิจวัตรประจำวันของผู้สูงอายุ</h3>
                    </div>
                    <div class="icon">
                        <i class="ni ni-check-bold"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Create News -->
    <div class="modal fade" id="createNewsModal" tabindex="-1" role="dialog" aria-labelledby="createNewsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createNewsModalLabel">เพิ่มข่าวสาร</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="title">หัวข้อ:</label>
                            <input type="text" id="title" name="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="content">เนื้อหา:</label>
                            <!-- Quill Editor Container -->
                            <div id="content-editor" style="height: 200px;"></div>
                            <!-- Hidden input to store Quill content -->
                            <input type="hidden" name="content" id="content">
                            {{-- <textarea id="content" name="content" class="form-control" rows="5"
                                required></textarea> --}}
                        </div>
                        <div class="form-group">
                            <input type="file" id="customFile" name="images[]" class="form-control-file" multiple
                                onchange="previewImages(event)" style="display: none;">
                            <button type="button" class="btn btn-login"
                                onclick="document.getElementById('customFile').click()">เลือกไฟล์รูป</button>
                        </div>
                        <div class="form-group">
                            <div id="imagePreview" style="display: flex; flex-wrap: wrap; gap: 10px;"></div>
                        </div>
                        <button type="submit" class="btn btn-success">บันทึก</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImages(event) {
            let imagePreview = document.getElementById('imagePreview');
            imagePreview.innerHTML = ''; // ล้างภาพเก่าออกก่อน

            for (let i = 0; i < event.target.files.length; i++) {
                let file = event.target.files[i];
                let reader = new FileReader();

                reader.onload = function (e) {
                    let img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.width = '100px';
                    img.style.height = '100px';
                    img.style.objectFit = 'cover';
                    img.className = 'img-thumbnail'; // ใช้ Bootstrap class เพื่อความสวยงาม
                    imagePreview.appendChild(img);
                };

                reader.readAsDataURL(file); // อ่านไฟล์เพื่อแสดงตัวอย่าง
            }
        }
    </script>


    <!-- Modal for Edit News -->
    @isset($newsItem)
        <div class="modal fade" id="editNewsModal" tabindex="-1" role="dialog" aria-labelledby="editNewsModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editNewsModalLabel">แก้ไขข่าวสาร</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editNewsForm" method="POST" action="{{ route('admin.news.update', $newsItem->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="title">หัวข้อ:</label>
                                <input type="text" id="edit-title" name="title" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="content">เนื้อหา:</label>
                                <div id="edit-content-editor" style="height: 200px;"></div>
                                <!-- Hidden input to store Quill content -->
                                <input type="hidden" name="content" id="edit-content">

                                {{-- <textarea id="edit-content" name="content" class="form-control" rows="5"
                                    required></textarea> --}}
                            </div>
                            <div class="form-group">
                                <label for="currentNewsImages">รูปภาพปัจจุบัน:</label>
                                <div id="currentNewsImages" style="display: flex; flex-wrap: wrap; gap: 5px;">
                                    <!-- รูปภาพจะแสดงที่นี่ -->
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="edit-images">อัปโหลดรูปภาพใหม่:</label>
                                <input type="file" id="edit-images" name="images[]" class="form-control-file" multiple
                                    style="display: none;">
                                <button type="button" class="btn btn-login"
                                    onclick="document.getElementById('edit-images').click()">เลือกไฟล์รูป</button>
                            </div>
                            <div class="form-group">
                                <div id="editImagePreview" style="display: flex; flex-wrap: wrap; gap: 10px;"></div>
                            </div>
                            <button type="submit" class="btn btn-success">บันทึก</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.getElementById('edit-images').addEventListener('change', function (event) {
                let editImagePreview = document.getElementById('editImagePreview');
                editImagePreview.innerHTML = ''; // ล้างภาพเก่าออกก่อน

                for (let i = 0; i < event.target.files.length; i++) {
                    let file = event.target.files[i];
                    let reader = new FileReader();

                    reader.onload = function (e) {
                        let img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.width = '100px';
                        img.style.height = '100px';
                        img.style.objectFit = 'cover';
                        img.className = 'img-thumbnail'; // ใช้ Bootstrap class เพื่อความสวยงาม
                        editImagePreview.appendChild(img);
                    };

                    reader.readAsDataURL(file); // อ่านไฟล์เพื่อแสดงตัวอย่าง
                }
            });
        </script>
    @endisset


    <!-- Modal for Create Slider -->
    <div class="modal fade" id="createSliderModal" tabindex="-1" role="dialog" aria-labelledby="createSliderModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createSliderModalLabel">เพิ่มรูปเลื่อนสไลด์</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <input type="file" id="sliderImageFile" name="image" class="form-control-file"
                                style="display: none;" required>
                            <button type="button" class="btn btn-login"
                                onclick="document.getElementById('sliderImageFile').click()">เลือกไฟล์รูป</button>
                        </div>
                        <div class="form-group">
                            <div id="sliderImagePreview" style="display: flex; flex-wrap: wrap; gap: 10px;"></div>
                        </div>
                        <button type="submit" class="btn btn-success">บันทึก</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('sliderImageFile').addEventListener('change', function (event) {
            let imagePreview = document.getElementById('sliderImagePreview');
            imagePreview.innerHTML = ''; // ล้างภาพเก่าออกก่อน

            for (let i = 0; i < event.target.files.length; i++) {
                let file = event.target.files[i];
                let reader = new FileReader();

                reader.onload = function (e) {
                    let img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.width = '100px';
                    img.style.height = '100px';
                    img.style.objectFit = 'cover';
                    img.className = 'img-thumbnail'; // ใช้ Bootstrap class เพื่อความสวยงาม
                    imagePreview.appendChild(img);
                };

                reader.readAsDataURL(file); // อ่านไฟล์เพื่อแสดงตัวอย่าง
            }
        });
    </script>


    <!-- Modal for View Slider -->
    <div class="modal fade" id="viewSliderModal" tabindex="-1" role="dialog" aria-labelledby="viewSliderModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewSliderModalLabel">แก้ไขรูปเลื่อนสไลด์</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="sliderContent">
                        @foreach ($sliders as $slider)
                            <tr>
                                <td>
                                    <img src="{{ url('storage/' . $slider->image) }}" alt="Slider Image" width="100">
                                </td>
                                <td>
                                    <button class="btn btn-warning" data-id="{{ $slider->id }}" data-toggle="modal"
                                        data-target="#editSliderModal"
                                        onclick="setSliderData('{{ $slider->id }}', '{{ $slider->image }}')">แก้ไข</button>
                                    <form action="{{ route('admin.sliders.destroy', $slider->id) }}" method="POST"
                                        id="delete-slider-form-{{ $slider->id }}" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger"
                                            onclick="confirmDeleteSlider('{{ $slider->id }}')">ลบ</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Edit Slider -->
    <div class="modal fade" id="editSliderModal" tabindex="-1" role="dialog" aria-labelledby="editSliderModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSliderModalLabel">แก้ไขรูปเลื่อนสไลด์</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editSliderForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="currentImage">รูปภาพปัจจุบัน:</label>
                            <img id="currentImage" src="" alt="Slider Image" width="100">
                        </div>
                        <div class="form-group">
                            <input type="file" id="editSliderImageFile" name="image" class="form-control-file"
                                style="display: none;">
                            <button type="button" class="btn btn-login"
                                onclick="document.getElementById('editSliderImageFile').click()">เลือกไฟล์รูป</button>
                        </div>
                        <div class="form-group">
                            <div id="editSliderImagePreview" style="display: flex; flex-wrap: wrap; gap: 10px;"></div>
                        </div>
                        <button type="submit" class="btn btn-success">บันทึก</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('layout.footer')
    <script>
        document.getElementById('editSliderImageFile').addEventListener('change', function (event) {
            let imagePreview = document.getElementById('editSliderImagePreview');
            imagePreview.innerHTML = ''; // ล้างภาพเก่าออกก่อน

            for (let i = 0; i < event.target.files.length; i++) {
                let file = event.target.files[i];
                let reader = new FileReader();

                reader.onload = function (e) {
                    let img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.width = '100px';
                    img.style.height = '100px';
                    img.style.objectFit = 'cover';
                    img.className = 'img-thumbnail'; // ใช้ Bootstrap class เพื่อความสวยงาม
                    imagePreview.appendChild(img);
                };

                reader.readAsDataURL(file); // อ่านไฟล์เพื่อแสดงตัวอย่าง
            }
        });
    </script>
    <script>
        let currentImageIndex = 0;
        let images = [];
        let zoomLevel = 1;
        let isDragging = false;
        let startX, startY;
        let translateX = 0,
            translateY = 0;

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

        var quillContent = new Quill('#content-editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ header: [1, 2, 3, 4, 5, 6, false] }],
                    ['bold', 'italic', 'underline'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    [{ script: 'sub' }, { script: 'super' }],
                    [{ indent: '-1' }, { indent: '+1' }],
                    [{ color: [] }, { background: [] }],
                    [{ font: [] }],
                    [{ align: [] }],
                    ['clean']
                ]
            }
        });

        var quillEditContent = new Quill('#edit-content-editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ header: [1, 2, 3, 4, 5, 6, false] }],
                    ['bold', 'italic', 'underline'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    [{ script: 'sub' }, { script: 'super' }],
                    [{ indent: '-1' }, { indent: '+1' }],
                    [{ color: [] }, { background: [] }],
                    [{ font: [] }],
                    [{ align: [] }],
                    ['clean']
                ]
            }
        });

        //ตัวนี้ไม่ได้ใช้งานแต่ใส่ไว้เพื่อได้เอาไปใช้งาน
        document.querySelector('#createNewsModal form').onsubmit = function () {
            document.getElementById('content').value = quillContent.root.innerHTML;
        };

        document.querySelector('#editNewsForm').onsubmit = function () {
            document.getElementById('edit-content').value = quillEditContent.root.innerHTML;
        };



        function showEditModal(id, title, content, images) {

            document.getElementById('editNewsForm').action = '{{ route("admin.news.update", ":id") }}'.replace(':id', id);
            document.getElementById('edit-title').value = title;

            quillEditContent.root.innerHTML = content;

            let imageContainer = document.getElementById('currentNewsImages');
            imageContainer.innerHTML = '';
            images.forEach(function (imagePath) {
                let img = document.createElement('img');
                img.src = `${imagePath}`;
                img.alt = 'Current News Image';
                img.className = 'img-thumbnail';
                img.style.width = '100px';
                img.style.height = '100px';
                imageContainer.appendChild(img);
            });

            $('#editNewsModal').modal('show');
        }

        function setSliderData(id, image) {
            const form = document.getElementById('editSliderForm');
            form.action = '{{ route("admin.sliders.update", ":id") }}'.replace(':id', id);
            document.getElementById('currentImage').src = `{{ url('storage') }}/${image}`;
        }

        function showModal(image, title, content) {
            document.getElementById('modalImage').src = image;
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('modalContent').textContent = content;
            $('#imageModal').modal('show');
        }

        function confirmDeleteNews(newsId) {
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: "คุณจะไม่สามารถย้อนกลับได้หลังจากลบ!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-news-form-' + newsId).submit();
                }
            });
        }

        function confirmDeleteSlider(sliderId) {
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: "คุณจะไม่สามารถย้อนกลับได้หลังจากลบ!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-slider-form-' + sliderId).submit();
                }
            });
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>
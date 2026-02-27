<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการข่าวสาร</title>

    <!-- Standard Fonts & Icons -->
    <link
        href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&family=Open+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Theme CSS -->
    <link href="{{ url('assets/css/argon-dashboard.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/nucleo-svg.css') }}" rel="stylesheet" />

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <x-admin-styles />
</head>

<body>
    @include('layout.nav')

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

    <div class="admin-buttons">
        <button class="btn btn-primary" data-toggle="modal"
            data-target="#createSliderModal">เพิ่มรูปเลื่อนสไลด์</button>
        <button class="btn btn-primary" data-toggle="modal" data-target="#viewSliderModal">แก้ไขรูปเลื่อนสไลด์</button>
    </div>

    <div class="container-fluid">
        <div class="row">
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
                                    @php
                                        $imageUrls = $newsItem->images->map(function ($img) {
                                            return url('storage/' . $img->image_path);
                                        })->toArray();
                                    @endphp
                                    <button class="btn btn-warning btn-sm edit-news-btn" style="margin-right: 10px;"
                                        data-id="{{ $newsItem->id }}" data-title='@json($newsItem->title)'
                                        data-content='@json($newsItem->content)'
                                        data-images='@json($imageUrls)'>แก้ไข</button>
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

    <x-admin-modals :sliders="$sliders" />
    @include('layout.footer')
    <x-scripts />
    <x-admin-scripts />
</body>

</html>
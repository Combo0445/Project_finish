<!-- modals.blade.php -->
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
                        <div id="content-editor" style="height: 200px;"></div>
                        <input type="hidden" name="content" id="content">
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

<!-- Modal for Edit News -->
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
                <form id="editNewsForm" method="POST" action="" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="edit-title">หัวข้อ:</label>
                        <input type="text" id="edit-title" name="title" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-content">เนื้อหา:</label>
                        <div id="edit-content-editor" style="height: 200px;"></div>
                        <input type="hidden" name="content" id="edit-content">
                    </div>
                    <div class="form-group">
                        <label for="currentNewsImages">รูปภาพปัจจุบัน:</label>
                        <div id="currentNewsImages" style="display: flex; flex-wrap: wrap; gap: 5px;"></div>
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

<!-- Modal for Create Slider -->
<div class="modal fade" id="createSliderModal" tabindex="-1" role="dialog"
    aria-labelledby="createSliderModalLabel" aria-hidden="true">
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

<!-- Modal เลือกรูป Slider -->
<div class="modal fade" id="selectSliderModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">เลือกรูปที่ต้องการแก้ไข</h5>

                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="row">

                    @foreach ($sliders as $slider)
                        <div class="col-md-4 text-center mb-3">

                            <img src="{{ asset('storage/' . $slider->image) }}" class="img-fluid rounded mb-2"
                                style="height:150px;object-fit:cover;">

                            <button class="btn btn-warning btn-sm"
                                onclick="openEditSlider('{{ $slider->id }}','{{ $slider->image }}')">

                                เลือกรูปนี้

                            </button>

                            <button type="button" class="btn btn-danger btn-sm"
                                onclick="confirmDeleteSlider('{{ $slider->id }}')">

                                ลบ

                            </button>
                            <form id="delete-slider-form-{{ $slider->id }}"
                                action="{{ route('admin.sliders.destroy', $slider->id) }}" method="POST"
                                style="display:none;">
                                @csrf
                                @method('DELETE')
                            </form>

                        </div>
                    @endforeach

                </div>

            </div>

        </div>
    </div>
</div>

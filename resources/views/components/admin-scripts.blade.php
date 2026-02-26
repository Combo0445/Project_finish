<script>
    function previewImages(event) {
        let imagePreview = document.getElementById('imagePreview');
        imagePreview.innerHTML = '';
        for (let i = 0; i < event.target.files.length; i++) {
            let file = event.target.files[i];
            let reader = new FileReader();
            reader.onload = function (e) {
                let img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '100px';
                img.style.height = '100px';
                img.style.objectFit = 'cover';
                img.className = 'img-thumbnail';
                imagePreview.appendChild(img);
            };
            reader.readAsDataURL(file);
        }
    }

    document.getElementById('edit-images')?.addEventListener('change', function (event) {
        let editImagePreview = document.getElementById('editImagePreview');
        editImagePreview.innerHTML = '';
        for (let i = 0; i < event.target.files.length; i++) {
            let file = event.target.files[i];
            let reader = new FileReader();
            reader.onload = function (e) {
                let img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '100px';
                img.style.height = '100px';
                img.style.objectFit = 'cover';
                img.className = 'img-thumbnail';
                editImagePreview.appendChild(img);
            };
            reader.readAsDataURL(file);
        }
    });

    document.getElementById('sliderImageFile')?.addEventListener('change', function (event) {
        let imagePreview = document.getElementById('sliderImagePreview');
        imagePreview.innerHTML = '';
        for (let i = 0; i < event.target.files.length; i++) {
            let file = event.target.files[i];
            let reader = new FileReader();
            reader.onload = function (e) {
                let img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '100px';
                img.style.height = '100px';
                img.style.objectFit = 'cover';
                img.className = 'img-thumbnail';
                imagePreview.appendChild(img);
            };
            reader.readAsDataURL(file);
        }
    });

    document.getElementById('editSliderImageFile')?.addEventListener('change', function (event) {
        let imagePreview = document.getElementById('editSliderImagePreview');
        imagePreview.innerHTML = '';
        for (let i = 0; i < event.target.files.length; i++) {
            let file = event.target.files[i];
            let reader = new FileReader();
            reader.onload = function (e) {
                let img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '100px';
                img.style.height = '100px';
                img.style.objectFit = 'cover';
                img.className = 'img-thumbnail';
                imagePreview.appendChild(img);
            };
            reader.readAsDataURL(file);
        }
    });

    let slideIndex = 0;
    const slides = document.querySelector('.slides');

    function showSlides() {
        if (!slides) return;
        const totalSlides = slides.children.length;
        slideIndex++;
        if (slideIndex >= totalSlides) slideIndex = 0;
        slides.style.transform = `translateX(${-slideIndex * 100}%)`;
    }

    function plusSlides(n) {
        if (!slides) return;
        slideIndex += n;
        const totalSlides = slides.children.length;
        if (slideIndex < 0) slideIndex = totalSlides - 1;
        else if (slideIndex >= totalSlides) slideIndex = 0;
        slides.style.transform = `translateX(${-slideIndex * 100}%)`;
    }

    if (slides) setInterval(showSlides, 3000);

    let quillContent, quillEditContent;
    if (document.getElementById('content-editor')) {
        quillContent = new Quill('#content-editor', {
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
    }

    if (document.getElementById('edit-content-editor')) {
        quillEditContent = new Quill('#edit-content-editor', {
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
    }

    document.querySelector('#createNewsModal form')?.addEventListener('submit', function () {
        if (quillContent) document.getElementById('content').value = quillContent.root.innerHTML;
    });

    document.querySelector('#editNewsForm')?.addEventListener('submit', function () {
        if (quillEditContent) document.getElementById('edit-content').value = quillEditContent.root.innerHTML;
    });

    function showEditModal(id, title, content, images) {
        const form = document.getElementById('editNewsForm');
        if (!form) return;
        form.action = '{{ route("admin.news.update", ":id") }}'.replace(':id', id);
        document.getElementById('edit-title').value = title;
        if (quillEditContent) quillEditContent.root.innerHTML = content;

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

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.edit-news-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const title = JSON.parse(this.getAttribute('data-title') || '""');
                const content = JSON.parse(this.getAttribute('data-content') || '""');
                const images = JSON.parse(this.getAttribute('data-images') || '[]');
                showEditModal(id, title, content, images);
            });
        });
    });

    function setSliderData(id, image) {
        const form = document.getElementById('editSliderForm');
        if (!form) return;
        form.action = '{{ route("admin.sliders.update", ":id") }}'.replace(':id', id);
        document.getElementById('currentImage').src = `{{ url('storage') }}/${image}`;
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
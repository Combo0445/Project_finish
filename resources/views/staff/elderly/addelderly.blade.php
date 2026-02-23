<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Elderly</title>
    <link href="{{ url('assets/css/argon-dashboard.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map {
            height: 400px;
            width: 100%;
            margin-bottom: 20px;
        }

        .back-button {
            background-color: #2c3e50;
            color: #fff;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-bottom: 20px;
            border: none;
        }

        .back-button:hover {
            background-color: #34495e;
        }

        .custom-file-upload {
            display: inline-block;
            padding: 6px 12px;
            cursor: pointer;
            background-color: #fb6340;
            color: white;
            border-radius: 5px;
            margin-top: 10px;
        }

        .custom-file-upload:hover {
            background-color: #ea3005;
        }

        #image-preview {
            margin-top: 15px;
            max-width: 200px;
            max-height: 200px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

    </style>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    @include('layout.nav')

    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h4>เพิ่มข้อมูลผู้สูงอายุ</h4>
            </div>
            <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif

                <form action="{{ route('store-elderly') }}" method="POST" enctype="multipart/form-data" onsubmit="concatenateAddress()">
                    @csrf
                    <div class="form-group">
                        <label for="Name_Elderly">ชื่อ-สกุล:</label>
                        <input type="text" id="Name_Elderly" name="Name_Elderly" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="Birthday">วัน/เดือน/ปีเกิด:</label>
                        <input type="date" id="Birthday" name="Birthday" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="Province">จังหวัด:</label>
                        <select id="Province" name="Province" class="form-control" required>
                            <option value="">เลือกจังหวัด</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="District">อำเภอ:</label>
                        <select id="District" name="District" class="form-control" required>
                            <option value="">เลือกอำเภอ</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="Subdistrict">ตำบล:</label>
                        <select id="Subdistrict" name="Subdistrict" class="form-control" required>
                            <option value="">เลือกตำบล</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="Address">ที่อยู่ (บ้านเลขที่, ถนน, ซอย):</label>
                        <textarea id="Address" name="Address" class="form-control" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="Postal_Code">รหัสไปรษณีย์:</label>
                        <input type="text" id="Postal_Code" name="Postal_Code" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label for="Phone_Elderly">เบอร์โทร:</label>
                        <input type="number" id="Phone_Elderly" name="Phone_Elderly" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="Image_Elderly">รูปภาพ:</label>
                        <label for="Image_Elderly" class="btn btn-login">
                            เลือกรูปภาพ
                        </label>
                        <input type="file" id="Image_Elderly" name="Image_Elderly" class="form-control" accept="image/*" style="display: none;" onchange="previewImage(event)">
                        <img id="image-preview" src="#" alt="Image Preview" style="display: none; max-width: 200px; margin-top: 10px;">
                    </div>

                    <!-- Leaflet Map for selecting location -->
                    <div id="map"></div>

                    <div class="form-group">
                        <label for="Latitude_position">Latitude Position:</label>
                        <input type="text" id="Latitude_position" name="Latitude_position" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="Longitude_position">Longitude Position:</label>
                        <input type="text" id="Longitude_position" name="Longitude_position" class="form-control">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success">ยืนยัน</button>
                        <a href="{{ route('staff-dashboard') }}" class="btn btn-danger">ยกเลิก</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let provincesData;

        // Load provinces data from the url path
        $.getJSON("{{ url('API/api_province_with_amphure_tambon.json') }}", function (data) {
            provincesData = data;
            let provinceSelect = $('#Province');

            // Populate province dropdown
            provincesData.forEach(function (province) {
                provinceSelect.append(`<option value="${province.id}">${province.name_th}</option>`);
            });
        });

        // Handle province selection
        $('#Province').change(function () {
            let provinceId = $(this).val();
            let districtSelect = $('#District');
            let subdistrictSelect = $('#Subdistrict');
            districtSelect.empty().append('<option value="">เลือกอำเภอ</option>');
            subdistrictSelect.empty().append('<option value="">เลือกตำบล</option>');
            $('#Postal_Code').val(''); // Clear the postal code

            let selectedProvince = provincesData.find(prov => prov.id == provinceId);
            if (selectedProvince) {
                selectedProvince.amphure.forEach(function (district) {
                    districtSelect.append(`<option value="${district.id}">${district.name_th}</option>`);
                });
            }
        });

        // Handle district selection
        $('#District').change(function () {
            let districtId = $(this).val();
            let subdistrictSelect = $('#Subdistrict');
            subdistrictSelect.empty().append('<option value="">เลือกตำบล</option>');
            $('#Postal_Code').val(''); // Clear the postal code

            let selectedProvince = provincesData.find(prov => prov.id == $('#Province').val());
            let selectedDistrict = selectedProvince.amphure.find(dist => dist.id == districtId);
            if (selectedDistrict) {
                selectedDistrict.tambon.forEach(function (subdistrict) {
                    subdistrictSelect.append(`<option value="${subdistrict.id}" data-zipcode="${subdistrict.zip_code}">${subdistrict.name_th}</option>`);
                });
            }
        });

        // Handle subdistrict (Tambon) selection and set postal code
        $('#Subdistrict').change(function () {
            let selectedZipCode = $(this).find(':selected').data('zipcode');
            $('#Postal_Code').val(selectedZipCode); // Set the postal code automatically
        });

        // Leaflet Map for selecting location
        var initialPosition = [14.971004543091427, 103.18498849868776]; // Initial map position set to Buriram, Thailand
        var map = L.map('map').setView(initialPosition, 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var marker = L.marker(initialPosition, { draggable: true }).addTo(map);

        map.on('click', function (e) {
            var clickedLocation = e.latlng;
            marker.setLatLng(clickedLocation);
            $('#Latitude_position').val(clickedLocation.lat);
            $('#Longitude_position').val(clickedLocation.lng);
        });

        marker.on('dragend', function (e) {
            var draggedLocation = e.target.getLatLng();
            $('#Latitude_position').val(draggedLocation.lat);
            $('#Longitude_position').val(draggedLocation.lng);
        });

        // Function to concatenate address parts
        function concatenateAddress() {
            let province = $('#Province option:selected').text();
            let district = $('#District option:selected').text();
            let subdistrict = $('#Subdistrict option:selected').text();
            let postalCode = $('#Postal_Code').val();
            let detailedAddress = $('#Address').val();

            let fullAddress = `จังหวัด${province} อำเภอ${district} ตำบล${subdistrict} ${detailedAddress} รหัสไปรษณีย์ ${postalCode}`;
            $('#Address').val(fullAddress);
        }

        function previewImage(event) {
            const imagePreview = document.getElementById('image-preview');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                imagePreview.style.display = 'none';
            }
        }

    </script>
</body>

</html>

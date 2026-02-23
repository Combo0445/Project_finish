<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Profile</title>
    <link href="{{ url('assets/css/argon-dashboard.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            padding-top: 70px;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h2 {
            margin-top: 0;
            color: #344767;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #344767;
        }

        input[type="text"],
        input[type="email"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            box-sizing: border-box;
            background-color: #fff;
            font-size: 14px;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="file"]:focus {
            border-color: #5e72e4;
            outline: none;
            box-shadow: 0 0 5px rgba(94, 114, 228, 0.5);
        }

        .edit-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #2dce89;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            margin-top: 20px;
        }

        .edit-button:hover {
            background-color: #24a866;
        }

        .custom-file-upload {
            display: inline-block;
            padding: 6px 12px;
            cursor: pointer;
            background-color: #1084ff;
            color: white;
            border-radius: 5px;
        }

        .custom-file-upload:hover {
            background-color: #1084ff;
        }

        #image-preview {
            margin-top: 15px;
            max-width: 200px;
            max-height: 200px;
            display: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

    </style>
</head>

<body class="bg-gray-100 g-sidenav-show">
    @include('layout.nav')

    <div class="container mt-5">
        <h4>แก้ไขโปรไฟล์</h4>
        <form action="{{ route('update-profile') }}" method="POST" enctype="multipart/form-data" onsubmit="concatenateAddress()">
            @csrf
            <div class="form-group">
                <label for="Name_User">ชื่อ-สกุล</label>
                <input type="text" id="Name_User" name="Name_User" value="{{ $user->Name_User }}" required>
            </div>
            <div class="form-group">
                <label for="Email">อีเมล</label>
                <input type="email" id="Email" name="Email" value="{{ $user->Email }}" required>
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
                <textarea id="Address" name="Address" class="form-control" required>{{ $user->Address }}</textarea>
            </div>

            <div class="form-group">
                <label for="Postal_Code">รหัสไปรษณีย์:</label>
                <input type="text" id="Postal_Code" name="Postal_Code" class="form-control" value="{{ $user->Postal_Code }}" readonly>
            </div>

            <div class="form-group">
                <label for="Phone">เบอร์โทร</label>
                <input type="text" id="Phone" name="Phone" value="{{ $user->Phone }}">
            </div>

            <div class="form-group">
                <label for="Image_User">รูปโปรไฟล์</label>
                <label for="Image_User" class="custom-file-upload">
                    เลือกรูปภาพ
                </label>
                <input type="file" id="Image_User" name="Image_User" class="form-control" accept="image/*" style="display: none;" onchange="previewImage(event)">
                <img id="image-preview" src="#" alt="Image Preview" />
            </div>



            <button type="submit" class="edit-button">อัพเดท</button>

        </form>
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

            // Set initial values based on the existing data
            extractAddressParts("{{ $user->Address }}");
        });

        function extractAddressParts(address) {
            const provinceSelect = $('#Province');
            const districtSelect = $('#District');
            const subdistrictSelect = $('#Subdistrict');
            const postalCodeField = $('#Postal_Code');
            const addressField = $('#Address');

            // Split the address string into parts
            const parts = address.match(/จังหวัด(.*?) อำเภอ(.*?) ตำบล(.*?) (.*?) รหัสไปรษณีย์ (\d+)/);

            if (parts && parts.length === 6) {
                const provinceName = parts[1];
                const districtName = parts[2];
                const subdistrictName = parts[3];
                const detailedAddress = parts[4]; // This is the part that includes the house number, street, etc.
                const postalCode = parts[5];

                // Set detailed address (house number, street, etc.)
                addressField.val(detailedAddress.trim());

                // Set province, district, subdistrict dropdowns
                const province = provincesData.find(prov => prov.name_th === provinceName.trim());
                if (province) {
                    provinceSelect.val(province.id).trigger('change');
                    setTimeout(function () {
                        const district = province.amphure.find(dist => dist.name_th === districtName.trim());
                        if (district) {
                            districtSelect.val(district.id).trigger('change');
                            setTimeout(function () {
                                const subdistrict = district.tambon.find(subdist => subdist.name_th === subdistrictName.trim());
                                if (subdistrict) {
                                    subdistrictSelect.val(subdistrict.id);

                                    // Set postal code after subdistrict is set
                                    postalCodeField.val(subdistrict.zip_code); // Set postal code based on selected subdistrict
                                }
                            }, ); //ตั้งการหน่วงเวลาในการทำงาน
                        }
                    }, ); // ตั้งการหน่วงเวลาในการทำงาน
                }
            }
        }

        // Handle province selection
        $('#Province').change(function () {
            let provinceId = $(this).val();
            let districtSelect = $('#District');
            let subdistrictSelect = $('#Subdistrict');
            districtSelect.empty().append('<option value="">เลือกอำเภอ</option>');
            subdistrictSelect.empty().append('<option value="">เลือกตำบล</option>');

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
            let postalCodeField = $('#Postal_Code'); // Select postal code field
            postalCodeField.val(''); // Clear the postal code

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

    // Set the postal code automatically after subdistrict is selected
    if (selectedZipCode) {
        $('#Postal_Code').val(selectedZipCode);
    }
});



        // Function to concatenate address parts before form submission
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

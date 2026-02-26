@props(['lat' => null, 'lng' => null, 'address' => null])

<div id="map-picker-container">
    <div id="map"
        style="height: 400px; width: 100%; margin-bottom: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
    </div>

    <input type="hidden" id="Lat" name="Lat" value="{{ $lat }}">
    <input type="hidden" id="Lng" name="Lng" value="{{ $lng }}">

    <div class="row">
        <div class="col-md-4">
            <div class="form-group mb-3">
                <label for="Province">จังหวัด:</label>
                <select id="Province" class="form-control" required>
                    <option value="">เลือกจังหวัด</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mb-3">
                <label for="District">อำเภอ:</label>
                <select id="District" class="form-control" required>
                    <option value="">เลือกอำเภอ</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mb-3">
                <label for="Subdistrict">ตำบล:</label>
                <select id="Subdistrict" class="form-control" required>
                    <option value="">เลือกตำบล</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-9">
            <div class="form-group mb-3">
                <label for="Address_Details">ที่อยู่ (บ้านเลขที่, ถนน, ซอย):</label>
                <textarea id="Address_Details" class="form-control" required placeholder="เช่น 123/45 หมู่ 1 ซอยสุขุมวิท 101"></textarea>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group mb-3">
                <label for="Postal_Code">รหัสไปรษณีย์:</label>
                <input type="text" id="Postal_Code" class="form-control" readonly>
            </div>
        </div>
    </div>

    <input type="hidden" id="Address" name="Address" value="{{ $address }}">
</div>

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let provincesData;
            const initialLat = {{ $lat ?? 13.7367 }};
            const initialLng = {{ $lng ?? 100.5231 }};
            const initialAddress = "{{ $address }}";

            const map = L.map('map').setView([initialLat, initialLng], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap contributors' }).addTo(map);

            let marker = L.marker([initialLat, initialLng], { draggable: true }).addTo(map);

            // Load Thai Addresses JSON
            $.getJSON("{{ url('API/api_province_with_amphure_tambon.json') }}", function (data) {
                provincesData = data;
                const provinceSelect = $('#Province');
                provincesData.forEach(p => provinceSelect.append(`<option value="${p.id}">${p.name_th}</option>`));
                
                if (initialAddress) {
                    parseExistingAddress(initialAddress);
                }
            });

            map.on('click', function (e) {
                marker.setLatLng(e.latlng);
                updateCoords(e.latlng);
            });

            marker.on('dragend', function (e) {
                updateCoords(e.target.getLatLng());
            });

            function updateCoords(latlng) {
                $('#Lat').val(latlng.lat);
                $('#Lng').val(latlng.lng);
                // Optional: Auto-fill from map click (reverse geocode)
                fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${latlng.lat}&lon=${latlng.lng}`)
                    .then(r => r.json())
                    .then(data => {
                        // We don't overwrite if user manually selected, but maybe helpful for filling details
                        if (data.address && !$('#Address_Details').val()) {
                            $('#Address_Details').val(data.display_name.split(',')[0]);
                        }
                    });
            }

            $('#Province').change(function () {
                const pid = $(this).val();
                const dSel = $('#District').empty().append('<option value="">เลือกอำเภอ</option>');
                const sSel = $('#Subdistrict').empty().append('<option value="">เลือกตำบล</option>');
                const prov = provincesData.find(p => p.id == pid);
                if (prov) prov.amphure.forEach(d => dSel.append(`<option value="${d.id}">${d.name_th}</option>`));
                window.concatenateAddress();
            });

            $('#District').change(function () {
                const did = $(this).val();
                const sSel = $('#Subdistrict').empty().append('<option value="">เลือกตำบล</option>');
                const prov = provincesData.find(p => p.id == $('#Province').val());
                const dist = prov.amphure.find(d => d.id == did);
                if (dist) dist.tambon.forEach(s => sSel.append(`<option value="${s.id}" data-zipcode="${s.zip_code}">${s.name_th}</option>`));
                window.concatenateAddress();
            });

            $('#Subdistrict').change(function () {
                $('#Postal_Code').val($(this).find(':selected').data('zipcode'));
                window.concatenateAddress();
            });

            $('#Address_Details').on('input', () => window.concatenateAddress());

            window.concatenateAddress = function() {
                const p = $('#Province option:selected').text();
                const d = $('#District option:selected').text();
                const s = $('#Subdistrict option:selected').text();
                const z = $('#Postal_Code').val();
                const t = $('#Address_Details').val();
                if (p && d && s) {
                    $('#Address').val(`จังหวัด${p} อำเภอ${d} ตำบล${s} ${t} รหัสไปรษณีย์ ${z}`);
                }
            };

            function parseExistingAddress(addr) {
                const parts = addr.match(/จังหวัด(.*?) อำเภอ(.*?) ตำบล(.*?) (.*?) รหัสไปรษณีย์ (\d+)/);
                if (parts && parts.length === 6) {
                    const pName = parts[1].trim();
                    const dName = parts[2].trim();
                    const sName = parts[3].trim();
                    const details = parts[4].trim();
                    const zip = parts[5].trim();

                    $('#Address_Details').val(details);
                    $('#Postal_Code').val(zip);

                    const prov = provincesData.find(p => p.name_th === pName);
                    if (prov) {
                        $('#Province').val(prov.id).trigger('change');
                        setTimeout(() => {
                            const dist = prov.amphure.find(d => d.name_th === dName);
                            if (dist) {
                                $('#District').val(dist.id).trigger('change');
                                setTimeout(() => {
                                    const sub = dist.tambon.find(s => s.name_th === sName);
                                    if (sub) $('#Subdistrict').val(sub.id);
                                }, 50);
                            }
                        }, 50);
                    }
                }
            }
        });
    </script>
@endpush
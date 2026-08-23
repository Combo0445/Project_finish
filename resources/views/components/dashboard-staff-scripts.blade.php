<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Chart.js: อายุ
    document.addEventListener('DOMContentLoaded', function () {
        const ageGroups = @json($ageGroups ?? []);
        if (Object.keys(ageGroups).length > 0) {
            new Chart(document.getElementById('ageBarChart'), {
                type: 'bar',
                data: {
                    labels: Object.keys(ageGroups),
                    datasets: [{
                        label: 'จำนวนผู้สูงอายุ',
                        data: Object.values(ageGroups),
                        backgroundColor: 'rgba(94, 114, 228, 0.2)',
                        borderColor: 'rgba(94, 114, 228, 1)',
                        borderWidth: 2,
                        borderRadius: 5,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { borderDash: [2], drawBorder: false } },
                        x: { grid: { display: false } }
                    }
                }
            });

            new Chart(document.getElementById('agePieChart'), {
                type: 'doughnut',
                data: {
                    labels: Object.keys(ageGroups),
                    datasets: [{
                        data: Object.values(ageGroups),
                        backgroundColor: [
                            'rgba(94, 114, 228, 0.8)',
                            'rgba(45, 206, 137, 0.8)',
                            'rgba(251, 99, 64, 0.8)',
                            'rgba(17, 205, 239, 0.8)'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } },
                    cutout: '70%'
                }
            });
        }
    });

    // Chart.js: ADL
    document.addEventListener('DOMContentLoaded', function () {
        const adlGroups = @json($adlGroups ?? []);
        if (Object.keys(adlGroups).length > 0) {
            new Chart(document.getElementById('adlBarChart'), {
                type: 'bar',
                data: {
                    labels: Object.keys(adlGroups),
                    datasets: [{
                        label: 'จำนวนผู้สูงอายุตามกลุ่ม ADL',
                        data: Object.values(adlGroups),
                        backgroundColor: 'rgba(45, 206, 137, 0.2)',
                        borderColor: 'rgba(45, 206, 137, 1)',
                        borderWidth: 2,
                        borderRadius: 5,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { beginAtZero: true, grid: { borderDash: [2], drawBorder: false } },
                        y: { grid: { display: false } }
                    }
                }
            });

            new Chart(document.getElementById('adlPieChart'), {
                type: 'pie',
                data: {
                    labels: Object.keys(adlGroups),
                    datasets: [{
                        data: Object.values(adlGroups),
                        backgroundColor: [
                            'rgba(45, 206, 137, 0.8)',
                            'rgba(251, 99, 64, 0.8)',
                            'rgba(245, 54, 92, 0.8)'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        }
    });

    // Leaflet Map
    document.addEventListener('DOMContentLoaded', function () {
        const elderlyLocations = @json($elderlyLocations ?? []);
        const mapDiv = document.getElementById('map');

        // ผู้สูงอายุที่ยังไม่ได้ปักหมุดตำแหน่ง (หรือไม่มีใครตรงกับตัวกรองปัจจุบัน) จะไม่มีพิกัด
        // ให้แสดง เดิมถ้าไม่มีข้อมูลเลยแผนที่จะไม่ถูกสร้างขึ้นและว่างเปล่าโดยไม่มีคำอธิบาย
        if (mapDiv && elderlyLocations.length === 0) {
            mapDiv.innerHTML = `
                <div class="d-flex flex-column align-items-center justify-content-center h-100 text-muted">
                    <i class="fas fa-map-marker-alt fa-2x mb-2"></i>
                    <span>ไม่พบข้อมูลตำแหน่งของผู้สูงอายุที่ตรงกับเงื่อนไขที่กรอง</span>
                </div>
            `;
        }

        if (mapDiv && elderlyLocations.length > 0) {
            const iconUrls = {
                'กลุ่มติดสังคม': 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-blue.png',
                'กลุ่มติดบ้าน': 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png',
                'กลุ่มติดเตียง': 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
                'ยังไม่ได้ประเมิน': 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-grey.png'
            };

            const icons = {};
            Object.entries(iconUrls).forEach(([group, url]) => {
                icons[group] = L.icon({
                    iconUrl: url,
                    shadowUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                });
            });

            // จุดเริ่มต้นที่ 14.971, 103.185 (บุรีรัมย์) เป็นแค่ fallback เผื่อไม่มีหมุดเลย --
            // ผู้สูงอายุอาจอยู่จังหวัดใดก็ได้ ถ้า setView ไว้ตายตัวแบบเดิม หมุดที่อยู่ไกล
            // (เช่น ฉะเชิงเทรา) จะถูกเพิ่มลงแผนที่จริง แต่อยู่นอกขอบเขตที่มองเห็น ทำให้ดูเหมือน
            // ไม่มีหมุดเลยทั้งที่จริงมีอยู่ -- ใช้ fitBounds ปรับมุมมองให้ครอบคลุมหมุดทั้งหมดแทน
            const map = L.map('map').setView([14.971, 103.185], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            const markers = [];
            elderlyLocations.forEach(loc => {
                const adl = loc.adlGroup in icons ? loc.adlGroup : 'ยังไม่ได้ประเมิน';
                const marker = L.marker([loc.latitude, loc.longitude], { icon: icons[adl] })
                    .addTo(map)
                    .bindPopup(
                        `<div style="font-family: 'Sarabun', sans-serif;">` +
                        `<h6 class="mb-1">\${loc.name}</h6>` +
                        `<p class="text-xs mb-1"><b>ที่อยู่:</b> \${loc.address}</p>` +
                        `<span class="badge bg-light text-dark">\${loc.adlGroup}</span>` +
                        `</div>`
                    );
                markers.push(marker);
            });

            if (markers.length > 0) {
                // maxZoom กันไม่ให้ซูมเข้าใกล้เกินไปตอนมีหมุดเดียวหรือหมุดอยู่ใกล้กันมาก
                map.fitBounds(L.featureGroup(markers).getBounds(), { padding: [30, 30], maxZoom: 15 });
            }
        }
    });
</script>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Staff Dashboard</title>
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <!-- Include DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
  <style>
    .chart-container {
      position: relative;
      height: 300px;
      width: 100%;
    }

    .chart-row {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }

    .chart-column {
      flex: 1;
      min-width: 300px;
    }
  </style>
</head>

<body>

  @include('layout.nav')

  <!-- Main Content -->
  <main class="main-content position-relative h-100 border-radius-lg">
    <div class="container-fluid py-4">



      <!-- Elderly Information -->
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
              <h4>ข้อมูลประวัติส่วนตัวของผู้สูงอายุ</h4>
              <div class="d-flex gap-2">

                <a href="add-elderly" class="btn btn-primary">
                  <i class="fas fa-plus"></i> เพิ่มผู้สูงอายุ
                </a>
                <a href="{{ route('elderly-report') }}" class="btn btn-success ml-2">
                  <i class="fas fa-print"></i>
                </a>
              </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table id="myTable" class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-center">รูป</th>
                      <th class="text-center">ชื่อ-นามสกุล</th>
                      <th class="text-center">อายุ</th>
                      <th class="text-center">ที่อยู่</th>
                      <th class="text-center">เบอร์โทร</th>
                      <th class="text-center">แบบประเมิน ADL</th>
                      <th class="text-center">รายงานการปฏิบัติงาน CG</th>
                      <th class="text-center">การจัดการ / การแจ้งเตือน</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($elderlies as $elderly)
                      <tr>
                        <td class="text-center">
                          @if ($elderly->Image_Elderly)
                            <img src="{{ url('storage/' . $elderly->Image_Elderly) }}" alt="Elderly Image" width="50">
                          @else
                            <img src="{{ url('storage/default.png') }}" alt="Elderly Image" width="50">
                          @endif
                        </td>
                        <td class="text-center">
                          {{ $elderly->Name_Elderly }}
                          @if ($elderly->needs_reassessment)
                            <span class="badge badge-sm bg-gradient-warning" title="ถึงกำหนดประเมินซ้ำ (3 เดือน)">!</span>
                          @endif
                          @if ($elderly->rapid_decline)
                            <span class="badge badge-sm bg-gradient-danger"
                              title="คะแนน ADL ลดลงอย่างรวดเร็ว (>= 2 คะแนน)">↓</span>
                          @endif
                        </td>
                        <td class="text-center">
                          {{ \Carbon\Carbon::parse($elderly->Birthday)->age }} ปี
                        </td>
                        <td class="text-center">{{ $elderly->Address }}</td>
                        <td class="text-center">{{ $elderly->Phone_Elderly }}</td>
                        <td class="text-center">
                          @if ($elderly->barthel_adl)
                            ทำแล้ว
                          @else
                            ยังไม่ทำ
                          @endif
                        </td>
                        <td class="text-center">
                          @if ($elderly->care_giver)
                            ทำแล้ว
                          @else
                            ยังไม่ทำ
                          @endif
                        </td>
                        <td class="text-center">
                          <a href="{{ route('search-location', ['id' => $elderly->ID_Elderly]) }}" target="_blank"
                            class="btn btn-info">ค้นหาที่อยู่</a>
                          <a href="{{ route('edit-elderly', ['id' => $elderly->ID_Elderly]) }}"
                            class="btn btn-warning">แก้ไข</a>
                          <button class="btn btn-secondary trend-btn" data-id="{{ $elderly->ID_Elderly }}">กราฟ</button>
                          <button type="button" class="btn btn-danger"
                            onclick="confirmDelete('{{ $elderly->ID_Elderly }}')">ลบ</button>
                          <form action="{{ route('delete-elderly', ['id' => $elderly->ID_Elderly]) }}" method="POST"
                            id="delete-form-{{ $elderly->ID_Elderly }}" style="display:none;">
                            @csrf
                            @method('DELETE')

                          </form>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6>จำนวนและสัดส่วนผู้สูงอายุตามช่วงอายุ</h6>
            </div>
            <div class="card-body">
              <div class="chart-row">
                <div class="chart-column">
                  <div class="chart-container">
                    <canvas id="ageBarChart"></canvas>
                  </div>
                </div>
                <div class="chart-column">
                  <div class="chart-container">
                    <canvas id="agePieChart"></canvas>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6>จำนวนและสัดส่วนของ ADL</h6>
            </div>
            <div class="card-body">
              <div class="chart-row">
                <div class="chart-column">
                  <div class="chart-container">
                    <canvas id="adlBarChart"></canvas>
                  </div>
                </div>
                <div class="chart-column">
                  <div class="chart-container">
                    <canvas id="adlPieChart"></canvas>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>




      <!-- Map -->
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6>แผนที่แสดงที่ตั้งผู้สูงอายุ</h6>
            </div>
            <div class="card-body">
              <div id="map" style="height: 500px;"></div>
            </div>
          </div>
        </div>
      </div>


  </main>

  <!-- ADL Trend Modal -->
  <div class="modal fade" id="adlTrendModal" tabindex="-1" role="dialog" aria-labelledby="adlTrendModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="adlTrendModalLabel">แนวโน้มคะแนน ADL - <span id="elderlyName"></span></h5>
          <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div style="height: 400px;">
            <canvas id="adlTrendChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Argon Dashboard JS -->
  <script src="{{ url('assets/js/argon-dashboard.js') }}"></script>
  <script src="{{ url('assets/js/core/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ url('assets/js/core/popper.min.js') }}"></script>
  <script src="{{ url('assets/js/core/bootstrap.min.js') }}"></script>
  <script src="{{ url('assets/js/plugins/bootstrap-notify.js') }}"></script>
  <script src="{{ url('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
  <script src="{{ url('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
  <!-- Include jQuery -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <!-- Include DataTables JS -->
  <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
  <script>
    // Initialize DataTable
    $(document).ready(function () {
      $('#myTable').DataTable({
        language: {
          paginate: { previous: "ก่อนหน้า", next: "ถัดไป" },
          search: "ค้นหา : ",
          lengthMenu: "แสดง _MENU_ รายการ",
          zeroRecords: "ไม่พบข้อมูล",
          info: "กำลังแสดงรายการ _START_ ถึง _END_ จากทั้งหมด _TOTAL_ รายการ",
          infoEmpty: "ไม่พบข้อมูล",
          infoFiltered: "(filtered from _MAX_ total records)"
        },
        dom: '<"row"<"col-sm-12 col-md-12"l><"col-sm-12 col-md-12"f>>t<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-3 d-flex justify-content-center"p>>'
      });
    });

    // Chart.js: อายุ
    document.addEventListener('DOMContentLoaded', function () {
      const ageGroups = @json($ageGroups);

      new Chart(document.getElementById('ageBarChart'), {
        type: 'bar',
        data: {
          labels: Object.keys(ageGroups),
          datasets: [{
            label: 'จำนวนผู้สูงอายุ',
            data: Object.values(ageGroups),
            backgroundColor: 'rgba(54,162,235,0.2)',
            borderColor: 'rgba(54,162,235,1)',
            borderWidth: 1
          }]
        },
        options: {
          scales: {
            y: { beginAtZero: true, display: false }
          }
        }
      });

      new Chart(document.getElementById('agePieChart'), {
        type: 'pie',
        data: {
          labels: Object.keys(ageGroups),
          datasets: [{
            data: Object.values(ageGroups),
            backgroundColor: [
              'rgba(255,99,132,0.2)',
              'rgba(54,162,235,0.2)',
              'rgba(255,206,86,0.2)',
              'rgba(75,192,192,0.2)'
            ],
            borderColor: [
              'rgba(255,99,132,1)',
              'rgba(54,162,235,1)',
              'rgba(255,206,86,1)',
              'rgba(75,192,192,1)'
            ],
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          plugins: { legend: { position: 'top' } }
        }
      });
    });

    // Chart.js: ADL
    document.addEventListener('DOMContentLoaded', function () {
      const adlGroups = @json($adlGroups);

      new Chart(document.getElementById('adlBarChart'), {
        type: 'bar',
        data: {
          labels: Object.keys(adlGroups),
          datasets: [{
            label: 'จำนวนผู้สูงอายุตามกลุ่ม ADL',
            data: Object.values(adlGroups),
            backgroundColor: 'rgba(75,192,192,0.2)',
            borderColor: 'rgba(75,192,192,1)',
            borderWidth: 1
          }]
        },
        options: {
          indexAxis: 'y',
          scales: {
            x: { beginAtZero: true, grid: { display: false } },
            y: { grid: { display: false } }
          }
        }
      });

      new Chart(document.getElementById('adlPieChart'), {
        type: 'doughnut',
        data: {
          labels: Object.keys(adlGroups),
          datasets: [{
            data: Object.values(adlGroups),
            backgroundColor: [
              'rgba(255,99,132,0.2)',
              'rgba(54,162,235,0.2)',
              'rgba(255,206,86,0.2)'
            ],
            borderColor: [
              'rgba(255,99,132,1)',
              'rgba(54,162,235,1)',
              'rgba(255,206,86,1)'
            ],
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          plugins: { legend: { position: 'top' } }
        }
      });
    });

    // Leaflet Map with colored circleMarkers by ADL group
    document.addEventListener('DOMContentLoaded', function () {
      const elderlyLocations = @json($elderlyLocations);

      // 1) กำหนด URL ของไอคอนสีต่างๆ (ใช้จาก leaflet-color-markers)
      const iconUrls = {
        'กลุ่มติดสังคม': 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-blue.png',
        'กลุ่มติดบ้าน': 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png',
        'กลุ่มติดเตียง': 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
        'ยังไม่ได้ประเมิน': 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-grey.png'
      };

      // 2) สร้าง Leaflet Icon object สำหรับแต่ละกลุ่ม
      const icons = {};
      Object.entries(iconUrls).forEach(([group, url]) => {
        icons[group] = L.icon({
          iconUrl: url,
          shadowUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-shadow.png',
          iconSize: [25, 41],   // ขนาดไอคอน
          iconAnchor: [12, 41],   // จุดที่ตรงกับพิกัด
          popupAnchor: [1, -34],   // ตำแหน่ง popup เมื่อคลิก
          shadowSize: [41, 41]
        });
      });

      // 3) สร้างแผนที่
      const map = L.map('map').setView([14.971, 103.185], 13);
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
      }).addTo(map);

      // 4) วนเพิ่ม Marker แต่ละจุด พร้อมไอคอนสีตามกลุ่ม ADL
      elderlyLocations.forEach(loc => {
        const adl = loc.adlGroup in icons ? loc.adlGroup : 'ยังไม่ได้ประเมิน';
        L.marker([loc.latitude, loc.longitude], { icon: icons[adl] })
          .addTo(map)
          .bindPopup(
            `<b>${loc.name}</b><br>` +
            `ที่อยู่: ${loc.address}<br>` +
            `<strong>ADL Group:</strong> ${loc.adlGroup}`
          );
      });
    });



    // Confirm delete action
    function confirmDelete(id) {
      if (confirm('ลบผู้สูงอายุนี้?')) {
        document.getElementById('delete-form-' + id).submit();
      }
    }

    // ADL Trend Chart
    let trendChart;
    $('.trend-btn').on('click', function () {
      const elderlyId = $(this).data('id');
      const modal = new bootstrap.Modal(document.getElementById('adlTrendModal'));

      fetch(`/elderly/${elderlyId}/adl-history`)
        .then(response => response.json())
        .then(data => {
          $('#elderlyName').text(data.name);
          const labels = data.history.map(item => new Date(item.created_at).toLocaleDateString('th-TH'));
          const scores = data.history.map(item => item.Score_ADL);

          const ctx = document.getElementById('adlTrendChart').getContext('2d');

          if (trendChart) {
            trendChart.destroy();
          }

          trendChart = new Chart(ctx, {
            type: 'line',
            data: {
              labels: labels,
              datasets: [{
                label: 'คะแนน ADL',
                data: scores,
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                fill: true,
                tension: 0.1
              }]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              scales: {
                y: {
                  beginAtZero: true,
                  max: 20
                }
              }
            }
          });

          modal.show();
        });
    });
  </script>

</body>

</html>
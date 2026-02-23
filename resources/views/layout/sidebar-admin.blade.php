<meta name="viewport" content="width=device-width, initial-scale=1.0">
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3" id="sidenav-main" style="background-color: #355e3b;">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="javascript:void(0)" style="text-align: center;">
            <h6 class="ms-1 font-weight-bold text-white">เมนูของผู้ดูแลระบบ</h6>
        </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link text-white {{ Request::is('/') ? 'active bg-gradient-primary' : '' }}" href="{{ url('/') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">home</i>
                    </div>
                    <span class="nav-link-text ms-1">หน้าหลัก</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ Request::is('admin-dashboard') ? 'active bg-gradient-primary' : '' }}" href="{{ route('admin.dashboard') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">person</i>
                    </div>
                    <span class="nav-link-text ms-1">จัดการข้อมูลผู้ใช้</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ Request::is('layout-admin') ? 'active bg-gradient-primary' : '' }}" href="{{ route('admin.layout-admin') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">assignment</i>
                    </div>
                    <span class="nav-link-text ms-1">จัดการข่าวสาร</span>
                </a>
            </li>

        </ul>
    </div>
</aside>

<!-- ปุ่มสำหรับดึง Sidebar กลับมา -->
<button class="sidebar-toggle-btn text-white" id="sidebar-toggle-btn" onclick="toggleSidebar()">☰</button>

<script>
    function toggleSidebar() {
        var sidebar = document.getElementById('sidenav-main');
        var toggleBtn = document.getElementById('sidebar-toggle-btn');
        sidebar.classList.toggle('collapsed');
        if (sidebar.classList.contains('collapsed')) {
            toggleBtn.style.left = '10px';
        } else {
            toggleBtn.style.left = '260px';
        }
    }
</script>

    <style>
        #sidenav-main {
            top: 80px;
            width: 50%;
            height: 80%;
        }

        .sidenav.collapsed {
            transform: translateX(-260px);
            transition: transform 0.3s ease;
        }

        .sidebar-toggle-btn {
            position: fixed;
            top: 130px; /* ปรับค่าตามต้องการเพื่อให้ปุ่มอยู่ในตำแหน่งที่เหมาะสม */
            left: 260px; /* เริ่มต้นที่ตำแหน่งปกติของ sidebar */
            z-index: 1100;
            background-color: #355e3b;
            border: none;
            color: white;
            cursor: pointer;
            padding: 10px;
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: left 0.3s ease;
        }

        .sidenav.collapsed ~ .sidebar-toggle-btn {
            left: 0px; /* เมื่อพับเก็บแล้ว, ปุ่มจะเลื่อนไปทางซ้าย */
        }

        @media only screen and (max-width: 767px) {
            .sidebar-toggle-btn {
                left: 10px; /* ขยับปุ่มไปทางซ้ายสำหรับหน้าจอมือถือ */
                top: 200px;  /* ปรับ top ให้ปุ่มอยู่ในตำแหน่งที่เหมาะสม */
            }

            #sidenav-main {
                top: 150px;
                width: 50%;
                height: 65%;
            }

            .sidenav.collapsed {
                transform: translateX(-220px); /* ซ่อน sidebar เมื่อพับเก็บใน mobile */
            }

            .sidenav ~ .sidebar-toggle-btn {
                left: 220px; /* ให้ปุ่มเลื่อนไปซ้ายเมื่อ sidebar ถูกซ่อนไว้ */
            }
        }
    </style>


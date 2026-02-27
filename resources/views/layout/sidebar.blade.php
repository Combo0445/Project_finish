@php
    $role = Auth::check() ? Auth::user()->Type_Personnel : '';
    $roleNames = [
        'Admin' => 'ผู้ดูแลระบบ',
        'Staff' => 'เจ้าหน้าที่',
        'Doctor' => 'นายแพทย์',
        'Pharmacist' => 'เภสัชกร'
    ];
    $currentRoleName = $roleNames[$role] ?? 'ผู้ใช้งาน';
@endphp

<!-- Standard Fonts & Icons for Sidebar -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<!-- Theme CSS mapping for icons if missed -->
<link href="{{ url('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
<link href="{{ url('assets/css/nucleo-svg.css') }}" rel="stylesheet" />

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3"
    id="sidenav-main" style="background-color: #355e3b;">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="javascript:void(0)" style="text-align: center;">
            <h6 class="ms-1 font-weight-bold text-white">เมนูของ{{ $currentRoleName }}</h6>
        </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="w-auto" id="sidenav-collapse-main" style="display: block;">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link text-white {{ Request::is('/') ? 'active bg-gradient-primary' : '' }}"
                    href="{{ url('/') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">home</i>
                    </div>
                    <span class="nav-link-text ms-1">หน้าหลัก</span>
                </a>
            </li>

            {{-- Dashboard Link --}}
            <li class="nav-item">
                <a class="nav-link text-white {{ Request::is('dashboard') ? 'active bg-gradient-primary' : '' }}"
                    href="{{ route('dashboard') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">dashboard</i>
                    </div>
                    <span class="nav-link-text ms-1">
                        @if($role === 'Admin') จัดการข้อมูลผู้ใช้
                        @elseif($role === 'Staff') จัดการข้อมูลผู้สูงอายุ
                        @elseif($role === 'Doctor') อ่านข้อมูลและให้คำแนะนำ
                        @elseif($role === 'Pharmacist') จัดการคลังยา
                        @else แดชบอร์ด
                        @endif
                    </span>
                </a>
            </li>

            {{-- Admin Specific --}}
            @if($role === 'Admin')
                <li class="nav-item">
                    <a class="nav-link text-white {{ Request::is('layout-admin') ? 'active bg-gradient-primary' : '' }}"
                        href="{{ route('admin.layout-admin') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">assignment</i>
                        </div>
                        <span class="nav-link-text ms-1">จัดการข่าวสาร</span>
                    </a>
                </li>
            @endif

            {{-- Staff Specific --}}
            @if($role === 'Staff')
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">
                        แบบประเมินผู้สูงอายุ</h6>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ Request::is('adl-show') ? 'active bg-gradient-primary' : '' }}"
                        href="{{ route('adl.index') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">assessment</i>
                        </div>
                        <span class="nav-link-text ms-1">ประเมินกิจวัตร (ADL)</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ Request::is('tai-show') ? 'active bg-gradient-primary' : '' }}"
                        href="{{ route('tai.index') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">elderly</i>
                        </div>
                        <span class="nav-link-text ms-1">ประเมินสุขภาพ (TAI)</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ Request::is('cg-show') ? 'active bg-gradient-primary' : '' }}"
                        href="{{ route('cg.index') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">assignment</i>
                        </div>
                        <span class="nav-link-text ms-1">ประเมินผู้ดูแล (CG)</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ Request::is('acg-show') ? 'active bg-gradient-primary' : '' }}"
                        href="{{ route('acg.index') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">directions_walk</i>
                        </div>
                        <span class="nav-link-text ms-1">บันทึกเยี่ยมบ้าน (ACG)</span>
                    </a>
                </li>

                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">รายงานและคำแนะนำ
                    </h6>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ Request::is('performance-report') ? 'active bg-gradient-primary' : '' }}"
                        href="{{ route('performanceReport.index') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">analytics</i>
                        </div>
                        <span class="nav-link-text ms-1">รายงานการปฏิบัติงาน</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ Request::is('care-instructions') && !request('unconfirmed') ? 'active bg-gradient-primary' : '' }}"
                        href="{{ route('care_instructions.index') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">fact_check</i>
                        </div>
                        <span class="nav-link-text ms-1">จัดการข้อมูลคำแนะนำ</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ request('unconfirmed') ? 'active bg-gradient-primary' : '' }}"
                        href="{{ route('care_instructions.index', ['unconfirmed' => 'true']) }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">announcement</i>
                        </div>
                        <span class="nav-link-text ms-1">คำแนะนำ (รอยืนยัน)</span>
                    </a>
                </li>
            @endif

            {{-- Pharmacist Specific --}}
            @if($role === 'Pharmacist' || $role === 'Admin')
                <li class="nav-item">
                    <a class="nav-link text-white {{ request('unconfirmed') ? 'active bg-gradient-primary' : '' }}"
                        href="{{ route('care_instructions.index', ['unconfirmed' => 'true']) }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">announcement</i>
                        </div>
                        <span class="nav-link-text ms-1">คำแนะนำ (รอยืนยัน)</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ Request::is('medicines*') ? 'active bg-gradient-primary' : '' }}"
                        href="{{ route('medicines.index') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">local_pharmacy</i>
                        </div>
                        <span class="nav-link-text ms-1">จัดการคลังยา</span>
                    </a>
                </li>
            @endif

            {{-- Doctor Specific --}}
            @if($role === 'Doctor')
                <li class="nav-item">
                    <a class="nav-link text-white {{ Request::is('care-instructions*') ? 'active bg-gradient-primary' : '' }}"
                        href="{{ route('care_instructions.index') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">person</i>
                        </div>
                        <span class="nav-link-text ms-1">จัดการข้อมูลคำแนะนำ</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</aside>

<button class="sidebar-toggle-btn text-white" id="sidebar-toggle-btn" onclick="toggleSidebar()">☰</button>

<script>
    function toggleSidebar() {
        var sidebar = document.getElementById('sidenav-main');
        var toggleBtn = document.getElementById('sidebar-toggle-btn');
        sidebar.classList.toggle('collapsed');
        toggleBtn.style.left = sidebar.classList.contains('collapsed') ? '10px' : '260px';
    }
</script>

<style>
    #sidenav-main {
        top: 80px;
        width: 250px;
        height: 80%;
        z-index: 1050;
    }

    .sidenav.collapsed {
        transform: translateX(-260px);
        transition: transform 0.3s ease;
    }

    .sidebar-toggle-btn {
        position: fixed;
        top: 130px;
        left: 260px;
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

    @media only screen and (max-width: 767px) {
        #sidenav-main {
            top: 150px;
            width: 60%;
            height: 65%;
        }

        .sidebar-toggle-btn {
            top: 200px;
            left: 10px;
        }

        .sidenav.collapsed {
            transform: translateX(-100%);
        }
    }
</style>
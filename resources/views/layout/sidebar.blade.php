@php
    $role = Auth::check() ? Auth::user()->Type_Personnel : '';
    $roleNames = [
        'Admin' => 'ผู้ดูแลระบบ',
        'Staff' => 'เจ้าหน้าที่',
        'Doctor' => 'นายแพทย์'
    ];
    $currentRoleName = $roleNames[$role] ?? 'ผู้ใช้งาน';
@endphp

<!-- Standard Fonts & Icons for Sidebar -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<!-- Theme CSS mapping for icons if missed -->
<link href="{{ url('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
<link href="{{ url('assets/css/nucleo-svg.css') }}" rel="stylesheet" />

<style>
    /* top/height account for the fixed 80px navbar above the sidebar --
       Argon's own sidenav assumes no separate top bar, so these override
       its defaults rather than fighting them with a hardcoded width */
    #sidenav-main {
        top: 80px;
        height: calc(100vh - 80px);
    }

    #sidenav-main .nav-link {
        border-radius: 0.5rem;
        margin: 2px 10px;
        transition: background-color 0.15s ease;
    }

    #sidenav-main .nav-link:not(.active):hover {
        background-color: rgba(255, 255, 255, 0.08);
    }

    #sidenav-main .nav-link.active {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.18);
    }

    #sidenav-main .nav-item h6 {
        letter-spacing: 0.06em;
    }

    /* Only meaningful on narrower screens: on desktop the sidebar is
       always docked (Argon's own CSS), so hide the toggle there */
    .sidebar-toggle-btn {
        display: none;
        position: fixed;
        top: 96px;
        left: 16px;
        z-index: 1051;
        width: 40px;
        height: 40px;
        border: none;
        border-radius: 50%;
        background-color: #355e3b;
        color: #fff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.25);
        align-items: center;
        justify-content: center;
    }

    @media only screen and (max-width: 1199.98px) {
        .sidebar-toggle-btn {
            display: flex;
        }

        #sidenav-main {
            top: 80px;
            height: calc(100vh - 80px);
        }
    }

    @media only screen and (max-width: 767px) {
        #sidenav-main {
            top: 60px;
            height: calc(100vh - 60px);
        }

        .sidebar-toggle-btn {
            top: 70px;
        }
    }
</style>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3"
    id="sidenav-main" style="background-color: #355e3b;">
    <div class="sidenav-header">
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
                <li class="nav-item">
                    <a class="nav-link text-white {{ Request::is('audit-logs') ? 'active bg-gradient-primary' : '' }}"
                        href="{{ route('audit-logs.index') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">history</i>
                        </div>
                        <span class="nav-link-text ms-1">ประวัติการใช้งาน (Audit Logs)</span>
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

    {{--
        Toggle button and script deliberately live INSIDE <aside>, not
        after it. Argon's desktop layout depends on the CSS sibling
        selector ".sidenav.fixed-start + .main-content" (see
        argon-dashboard.css) to push page content over -- anything
        rendered as a sibling between </aside> and <main class="main-content">
        breaks that rule and makes the sidebar overlap the page instead
        of docking beside it, which is what was happening before.
    --}}
    <button class="sidebar-toggle-btn" id="sidebar-toggle-btn" type="button" onclick="toggleSidebar()"
        title="เปิด/ปิดเมนู" aria-label="เปิด/ปิดเมนู">
        <i class="fas fa-bars"></i>
    </button>

    <script>
        // Uses Argon Dashboard's own sidenav toggle mechanism (the
        // g-sidenav-pinned body class) instead of a separate ad hoc
        // collapsed/transform system, so it doesn't fight the framework's
        // built-in responsive behavior.
        function toggleSidebar() {
            document.body.classList.toggle('g-sidenav-pinned');
        }
    </script>
</aside>

<div class="navbar">
    <div class="logo">
        <img src="{{ url('images/Logo.png') }}" alt="Logo" style="height: 60px; width: auto; margin-right: 15px;">
        <div class="logo-text">
            <span
                style="font-size: 16px; font-weight: 600; color: #fff; display: block; line-height: 1.2;">ระบบประเมินความสามารถในการดำเนินกิจวัตรประจำวันของผู้สูงอายุ</span>
            <span style="font-size: 13px; font-weight: 400; color: #e6fffc; opacity: 0.9;">Barthel Activities of Daily
                Living</span>
        </div>
    </div>

    <div class="nav-links">
        <a href="{{ url('/') }}">หน้าหลัก</a>
        <div class="dropdown">
            <a href="{{ route('about') }}">เกี่ยวกับ</a>
            <div class="dropdown-content">
                <a href="{{ route('history') }}">ประวัติสำนักงาน</a>
                <a href="{{ route('personnel') }}">คณะบุคลากร</a>
                <a href="{{ route('vision') }}">วิสัยทัศน์/พันธกิจ</a>
            </div>
        </div>
        <a href="{{ route('contact') }}">ติดต่อเรา</a>
    </div>

    @if (Auth::check())
        <div class="user-info">
            @php
                $user = Auth::user();
                $avatarSrc = '';
                if ($user && $user->Image_User) {
                    $avatarSrc = url($user->Image_User);
                } else {
                    $type = $user->Type_Personnel ?? '';
                    if ($type === 'Admin') {
                        $avatarSrc = asset('images-user/Admin.jpg');
                    } elseif ($type === 'Staff') {
                        $avatarSrc = asset('images-user/Staff.png');
                    } elseif ($type === 'Doctor') {
                        $avatarSrc = asset('images-user/Doctor.png');
                    } else {
                        $avatarSrc = asset('images/Logo.png');
                    }
                }
            @endphp
            <a href="{{ url('profile-user') }}">
                <img src="{{ $avatarSrc }}" alt="Profile Image">
                <span>{{ $user->Name_User ?? '' }}</span>
            </a>
            <div class="notifications dropdown">
                @php
                    $notifications = \App\Models\CareInstruction::where('Name_Staff', Auth::user()->Name_User)
                        ->whereNull('Confirm')
                        ->get();
                @endphp
                <i class="fas fa-bell {{ $notifications->isNotEmpty() && !Request::is('staff-ci') ? 'shake' : '' }}"
                    onclick="toggleNotificationDropdown()"></i>
                <div class="dropdown-content" id="notificationDropdown">
                    @if ($notifications->isEmpty())
                        <a href="#">ไม่มีการแจ้งเตือน</a>
                    @else
                        @foreach ($notifications as $notification)
                            <a href="{{ url('staff-ci') }}">{{ $notification->Name_Elderly }} -
                                {{ $notification->Care_instructions }}</a>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="dropdown">
                <i class="fas fa-cog" onclick="toggleDropdown()"></i>
                <div class="dropdown-content" id="userDropdown" style="width: 200px; padding: 10px;">
                    <a href="{{ url('profile-user') }}"
                        style="padding: 12px 20px; font-size: 16px; display: block;">โปรไฟล์</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            style="all: unset; cursor: pointer; color: #fff; text-decoration: none; padding: 12px 20px; font-size: 16px; display: block; border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
                            ออกจากระบบ
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @else
        <a href="{{ url('login') }}" class="btn btn-success">เข้าสู่ระบบ</a>
    @endif
</div>

<script>
    function toggleDropdown() {
        var dropdown = document.getElementById("userDropdown");
        dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
    }

    function toggleNotificationDropdown() {
        var dropdown = document.getElementById("notificationDropdown");
        dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
    }

    window.onclick = function (event) {
        if (!event.target.matches('.user-info img') && !event.target.matches('.user-info span') && !event.target
            .matches('.fa-cog') && !event.target.matches('.fa-bell')) {
            var dropdowns = document.getElementsByClassName("dropdown");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.style.display === "block") {
                    openDropdown.style.display = "none";
                }
            }
        }
    }
</script>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="{{ url('assets/css/argon-dashboard.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/nucleo-svg.css') }}" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>

        /* General mobile styles */
@media only screen and (max-width: 767px) {
    .navbar {
        padding: 5px 10px; /* Reduce padding for mobile */
        font-size: 12px; /* Smaller font size */
    }

    .navbar .logo img {
        height: 30px; /* Smaller logo for mobile */
    }

    .navbar .nav-links a {
        font-size: 12px; /* Smaller font size for nav links */
        margin-left: 10px; /* Reduce spacing between nav links */
    }

    .navbar .user-info img {
        width: 25px; /* Smaller user profile image */
        height: 25px;
    }

    /* Add margin to push content away from the navbar */
    body {
        margin-top: 60px; /* Adjust this value to create space between the navbar and the content */
    }

    /* Alternatively, if the content is inside a container, you can add margin or padding there */
    .content {
        margin-top: 60px; /* Push the content down from the navbar */
    }
}


/* Tablet Styles */
@media only screen and (min-width: 768px) and (max-width: 1024px) {
    .navbar {
        padding: 15px;
        font-size: 16px;
    }

    .navbar .logo img {
        height: 50px;
    }

    .navbar .nav-links a {
        font-size: 14px;
    }
}

        .dataTables_wrapper .dataTables_paginate {
            display: flex;
            justify-content: center;

        }

        body {
            font-family: 'Open Sans', sans-serif;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            padding-top: 80px;
            color: #67748e; /* Matches the color from the template */
            background-color: #fff;
          }


        .navbar {
            width: 100%;
            background-color: #014421;
            color: #fff;
            padding: 15px;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .navbar .logo {
            display: flex;
            align-items: center;
        }

        .navbar .logo img {
            height: 60px;
            margin-right: 10px;
        }

        .navbar .nav-links a {
            color: #fff;
            text-decoration: none;
            margin-left: 20px;
            transition: color 0.3s ease;
        }

        .navbar .nav-links a:hover {
            color: #a2fff6;
        }

        .navbar .user-info {
            display: flex;
            align-items: center;
            position: relative;
        }

        .navbar .user-info a {
            display: flex;
            align-items: center;
            color: #fff;
            text-decoration: none;
            margin-right: 10px;
        }

        .navbar .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            cursor: pointer;
        }

        .navbar .user-info .dropdown,
        .navbar .notifications .dropdown {
            display: none;
            position: absolute;
            top: 50px;
            right: 0;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1001;
            border-radius: 8px;
        }

        .navbar .user-info .dropdown a,
        .navbar .notifications .dropdown a {
            display: block;
            padding: 10px 20px;
            color: #355e3b;
            text-decoration: none;
            border-bottom: 1px solid #f4f4f4;
            transition: background-color 0.3s ease;
        }

        .navbar .user-info .dropdown a:last-child,
        .navbar .notifications .dropdown a:last-child {
            border-bottom: none;
        }

        .navbar .user-info .dropdown a:hover,
        .navbar .notifications .dropdown a:hover {
            background-color: #f4f4f4;
        }

        .fa-cog,
        .fa-bell {
            cursor: pointer;
            margin-right: 10px;
            font-size: 1.5em;
            transition: transform 0.3s ease;
        }

        .fa-cog:hover {
            transform: rotate(90deg);
            color: #a2fff6;
        }

        .shake {
            animation: shake 0.5s;
            animation-iteration-count: infinite;
        }

        @keyframes shake {
            0% {
                transform: rotate(0deg);
            }

            25% {
                transform: rotate(-15deg);
            }

            50% {
                transform: rotate(0deg);
            }

            75% {
                transform: rotate(15deg);
            }

            100% {
                transform: rotate(0deg);
            }
        }

        .sidebar {
            position: fixed;
            top: 70px;
            left: 0;
            width: 250px;
            height: 100%;
            background-color: #f8f9fa;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            padding-top: 20px;
            transition: transform 0.3s ease;
        }

        .sidebar .toggle-btn {
            position: absolute;
            top: 20px;
            right: -30px;
            background-color: #355e3b;
            color: #fff;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 0 5px 5px 0;
        }

        .sidebar.collapsed {
            transform: translateX(-260px);
        }
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #014421;
            min-width: 200px;
            top : 100%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1;
        }

        .dropdown-content a {
            color: #355e3b;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #037138;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .menu-item-has-children {
            position: relative;
        }



    </style>
</head>

<body>
    <div class="navbar">
        <div class="logo">
            <img src="{{ url('images/Logo.png') }}" alt="Logo">
            <div>
                <span style="font-size: 18px; font-weight: 500; color: #fff;">ระบบประเมินความสามารถในการดำเนินกิจวัตรประจำวันของผู้สูงอายุ</span>
                <br>
                <span style="font-size: 14px; font-weight: 400; color: #e6fffc;">Barthel Activities of Daily Living</span>
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
                <a href="{{ url('profile-user') }}">
                    <img src="{{ url(Auth::user()->Image_User) }}" alt="Profile Image">
                    <span>{{ Auth::user()->Name_User }}</span>
                </a>
                <div class="notifications">
                    @php
                        $notifications = \App\Models\CareInstruction::where('Name_Staff', Auth::user()->Name_User)
                            ->whereNull('Confirm')
                            ->get();
                    @endphp
                    <i class="fas fa-bell {{ $notifications->isNotEmpty() && !Request::is('staff-ci') ? 'shake' : '' }}"
                        onclick="toggleNotificationDropdown()"></i>
                    <div class="dropdown" id="notificationDropdown">
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
                <i class="fas fa-cog" onclick="toggleDropdown()"></i>
                <div class="dropdown" id="userDropdown" style="width: 200px; padding: 10px;">
                    <a href="{{ url('profile-user') }}"
                        style="padding: 12px 20px; font-size: 16px; display: block;">โปรไฟล์</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            style="all: unset; cursor: pointer; color: #355e3b; text-decoration: none; padding: 12px 20px; font-size: 16px; display: block;">
                            ออกจากระบบ
                        </button>
                    </form>
                </div>

            </div>
        @else
            <a href="{{ url('login') }}" class="btn btn-success">เข้าสู่ระบบ</a>
        @endif
    </div>

    @if (Auth::check())
        @if (Auth::user()->Type_Personnel === 'Admin')
            @include('layout.sidebar-admin')
        @elseif (Auth::user()->Type_Personnel === 'Staff')
            @include('layout.sidebar-staff')
        @elseif (Auth::user()->Type_Personnel === 'Doctor')
            @include('layout.sidebar-doctor')
        @endif
    @endif


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function toggleDropdown() {
            var dropdown = document.getElementById("userDropdown");
            dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
        }

        function toggleNotificationDropdown() {
            var dropdown = document.getElementById("notificationDropdown");
            dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
        }

        window.onclick = function(event) {
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

        function toggleSidebar() {
            var sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('collapsed');
        }

        function toggleSidebar() {
            var sidebar = document.querySelector('.sidenav');
            sidebar.classList.toggle('collapsed');
        }

        document.querySelector('.dropbtn').addEventListener('click', function() {
            var dropdownContent = this.nextElementSibling;
            dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
        });

        window.onclick = function(event) {
            if (!event.target.matches('.dropbtn')) {
                var dropdowns = document.getElementsByClassName('dropdown-content');
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.style.display === 'block') {
                        openDropdown.style.display = 'none';
                    }
                }
            }
        }
    </script>

</body>

</html>

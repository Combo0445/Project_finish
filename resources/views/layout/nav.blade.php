@php
    // Unified Navigation Component
@endphp

<style>
    /* Override Argon Dashboard CSS variable to use Sarabun for Thai */
    :root {
        --bs-font-sans-serif: 'Sarabun', 'Open Sans', sans-serif;
    }

    /* Global Sidebar/Navbar Spacer for all pages */
    body {
        font-family: 'Sarabun', 'Open Sans', sans-serif !important;
        padding-top: 80px !important;
    }

    @media only screen and (max-width: 767px) {
        body {
            padding-top: 60px !important;
        }
    }

    /* Core Navbar Styling */
    .navbar {
        width: 100%;
        height: 80px;
        background-color: #014421;
        color: #fff;
        padding: 0 20px;
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
        max-height: 70px;
    }

    .navbar .logo img {
        height: 55px;
        width: auto;
        margin-right: 15px;
        object-fit: contain;
    }

    .logo-text {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .navbar .nav-links a {
        color: #fff !important;
        /* Force white color */
        text-decoration: none;
        margin-left: 20px;
        transition: color 0.3s ease;
    }

    .navbar .nav-links a:hover {
        color: #a2fff6 !important;
    }

    .navbar .user-info {
        display: flex;
        align-items: center;
        position: relative;
    }

    .navbar .user-info a {
        display: flex;
        align-items: center;
        color: #fff !important;
        /* Force white color for name */
        text-decoration: none;
        margin-right: 10px;
    }

    .navbar .user-info img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 10px;
        cursor: pointer;
        object-fit: cover;
    }

    /* Standardize dropdowns */
    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #014421;
        min-width: 210px;
        top: 100%;
        right: 0;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        z-index: 1001;
        border-radius: 4px;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .dropdown-content a {
        color: #fff !important;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .dropdown-content a:hover {
        background-color: #037138;
    }

    .fa-cog,
    .fa-bell {
        cursor: pointer;
        margin-right: 15px;
        font-size: 1.2rem;
        color: #fff !important;
    }

    .shake {
        animation: shake 0.5s infinite;
    }

    @keyframes shake {

        0%,
        100% {
            transform: rotate(0deg);
        }

        25% {
            transform: rotate(-10deg);
        }

        75% {
            transform: rotate(10deg);
        }
    }

    /* Mobile adjustments */
    @media only screen and (max-width: 767px) {
        .navbar {
            height: 60px;
            padding: 0 10px;
        }

        .navbar .logo img {
            height: 35px;
        }

        .logo-text span:first-child {
            font-size: 12px !important;
        }

        .logo-text span:last-child {
            display: none;
        }

        .nav-links {
            display: none;
        }
    }
</style>

@include('layout.nav-content')

{{--
    Sidebar must be the very last thing this partial renders (nothing --
    not even a <script>/<style> -- after its closing </aside>), because
    Argon Dashboard's desktop layout relies on the CSS sibling selector
    ".sidenav.fixed-start + .main-content" to push page content over.
    toggleDropdown()/toggleNotificationDropdown()/window.onclick above
    used to be duplicated here too; removed since layout.nav-content
    already defines the identical functions.
--}}
@if (Auth::check())
    @include('layout.sidebar')
@endif
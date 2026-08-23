<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'ระบบประเมินผู้สูงอายุที่มีภาวะพึ่งพิง (ADL)')</title>

    <!-- Standard Styles -->
    <link
        href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&family=Open+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="{{ url('assets/css/argon-dashboard.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    @stack('styles')

    <style>
        /* Override Argon Dashboard CSS variable to use Sarabun for Thai */
        :root {
            --bs-font-sans-serif: 'Sarabun', 'Open Sans', sans-serif;
        }

        body {
            font-family: 'Sarabun', 'Open Sans', sans-serif !important;
            padding-top: 80px;
            /* Space for fixed navbar */
            color: #67748e;
            background-color: #f8f9fa;
        }

        /* Mobile adjustment */
        @media only screen and (max-width: 767px) {
            body {
                padding-top: 60px;
            }
        }

        /* Staff Accessibility Mode (Level 1 UX for อสม.) */
        body.staff-accessible {
            font-size: 1.15rem;
            /* ~20% larger base font */
            color: #333;
            /* Darker text for higher contrast */
        }

        body.staff-accessible h1,
        body.staff-accessible h2,
        body.staff-accessible h3,
        body.staff-accessible h4,
        body.staff-accessible h5,
        body.staff-accessible h6 {
            font-weight: 700 !important;
            color: #222;
        }

        body.staff-accessible .btn {
            font-size: 1.1rem !important;
            /* Larger text on buttons */
            padding: 12px 24px !important;
            /* Bigger tap targets */
            font-weight: bold;
        }

        body.staff-accessible table td,
        body.staff-accessible table th {
            font-size: 1.1rem !important;
            padding: 15px !important;
            /* Taller rows for easy tapping */
        }

        body.staff-accessible .badge {
            font-size: 0.95rem !important;
            padding: 8px 12px;
        }

        body.staff-accessible .nav-link-text {
            font-size: 1.15rem !important;
            font-weight: 600;
        }
    </style>
</head>

@php
    $bodyClasses = [];
    if (auth()->check()) {
        // Enables Argon Dashboard's sidenav layout system: on desktop the
        // sidebar docks permanently and pushes .main-content over; on
        // smaller screens it starts hidden and the toggle button reveals it
        $bodyClasses[] = 'g-sidenav-show';
    }
    if (auth()->check() && auth()->user()->Type_Personnel === 'Staff') {
        $bodyClasses[] = 'staff-accessible';
    }
    $bodyClass = implode(' ', $bodyClasses);
@endphp

<body class="{{ $bodyClass }}">
    @include('layout.nav')

    <main class="main-content position-relative h-100 border-radius-lg">
        <div class="container-fluid py-4">
            @yield('content')
        </div>
    </main>

    <!-- Core Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('modals')
    @stack('scripts')
</body>

</html>
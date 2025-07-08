<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/sass/app.scss'])

    <!-- CDNs -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>
<body>
    <div id="app">
        @include('components.navbar.dashboardNavbar')
        <div class="container-fluid">
            <div class="row">
                <div class="offcanvas offcanvas-start shadow d-xl-none" tabindex="-1" id="sidebarOffcanvas">
                    <div class="offcanvas-body p-3">
                        @include('components.sidebar.institution')
                    </div>
                </div>
                <div id="desktopSidebar" class="col-xl-3 bg-white shadow-sm">
                    <div class="p-3">
                        @include('components.sidebar.institution')
                    </div>
                </div>
                
                <div class="col-xl-9 flex-grow-1">
                    <main class="p-4">
                        @yield('content')
                        @vite(['resources/js/app.js'])
                    </main>
                </div>
            </div>
        </div>
    </div>
    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    {{-- <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof bootstrap === 'undefined') {
                console.log('Bootstrap JavaScript is not loaded');
            } else {
                console.log('Bootstrap JavaScript is loaded:', bootstrap);
                const testDropdown = document.querySelector('[data-bs-toggle="dropdown"]');
                if (testDropdown) {
                    const bsDropdown = new bootstrap.Dropdown(testDropdown);
                    console.log('Test Dropdown Initialized:', bsDropdown);
                } else {
                    console.log('No dropdown toggle found');
                }
            }
        });
    </script> --}}
</body>
</html>
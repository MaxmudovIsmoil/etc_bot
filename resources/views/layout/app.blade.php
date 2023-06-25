<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" id="js_meta" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Example') }}</title>

    <link rel="android-chrome" sizes="192x192" href="{{ asset('android-chrome-192x192.png') }}">
    <link rel="android-chrome" sizes="512x512" href="{{ asset('android-chrome-512x512.png') }}">
    <link rel="apple-touch-icon" sizes="512x512" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <!-- Bootstrap CSS -->
    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />

    <!-- Datatables CSS -->
    <link rel="stylesheet" href="{{ asset('datatable/dataTables.bootstrap5.min.css') }}">
    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">
    <!-- Select2 CSS -->
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    @yield('style')

    <style>
        .active {
            color: #0966b0 !important;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg bg-shadow px-2 mb-5">
    <div class="container-fluid">

        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <ul class="navbar-nav">

                <li class="nav-item">
                    <a class="nav-link fw-semibold @if(Request::segment(1) == 'order') active @endif" href="{{ route('order.index') }}">
                        <i class="fa-regular fa-cart-shopping"></i> Orders
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold @if(Request::segment(1) == 'client') active @endif" href="{{ route('client.index') }}">
                        <i class="fa-duotone fa-users"></i> Clients
                    </a>
                </li>
            </ul>


            <ul class="navbar-nav ms-auto">

                <!-- Authentication Links -->
                <a class="nav-link fw-semibold" href=""
                   onclick="event.preventDefault();
                                          document.getElementById('logout-form').submit();">
                    <i class="fa-solid fa-right-from-bracket"></i> {{ __('Выход') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>

            </ul>
        </div>

    </div>
</nav>

@yield('content')

<!-- jQuery -->
<script src="{{ asset('jquery/jquery-3.6.1.min.js') }}"></script>
<!-- Bootstrap JS -->
<script src="{{ asset('popper/popper.min.js') }}"></script>
<script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>

<!-- Datatables JS -->
<script src="{{ asset('datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('datatable/dataTables.bootstrap5.min.js') }}"></script>

<!-- Main JS -->
<script src="{{ asset('js/delete_function.js') }}"></script>
<script src="{{ asset('js/validate_function.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>

@yield('script')

</body>

</html>

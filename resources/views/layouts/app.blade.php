<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'EventEase') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;600&family=Saira+Stencil+One&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --bs-primary: #9f1d1d;
            --heading-font: 'Saira Stencil One', sans-serif;
            --body-font: 'Urbanist', sans-serif;
            --bs-dark: #000;
        }

        body {
            font-family: var(--body-font);
            background-color: #f8f9fa;
            margin: 0;
        }

        .navbar-custom {
            background-color: white;
            border-bottom: 1px solid #eee;
        }

        .navbar-brand {
            font-family: var(--heading-font);
            font-size: 1.8rem;
            color: var(--bs-dark);
        }

        .navbar-nav .nav-link {
            font-size: 1rem;
            color: var(--bs-dark);
            margin-right: 1rem;
            position: relative;
        }

        .navbar-nav .nav-link:hover {
            color: var(--bs-primary);
        }

        .navbar-nav .nav-link::after {
            content: '';
            display: block;
            width: 0%;
            height: 2px;
            background: var(--bs-primary);
            transition: 0.3s ease;
            margin-top: 4px;
        }

        .navbar-nav .nav-link:hover::after {
            width: 100%;
        }

        .nav-icons {
            font-size: 1.1rem;
            margin-left: 1rem;
            color: var(--bs-dark);
        }

        .nav-icons:hover {
            color: var(--bs-primary);
        }

        footer {
            background-color: #f1f1f1;
            padding: 20px 0;
            text-align: center;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div id="app">
        <!-- Modern Navbar -->
        <nav class="navbar navbar-expand-lg navbar-custom shadow-sm">
            <div class="container">
                <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                    EventEase
                </a>
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                    <span class="navbar-toggler-icon">
                        <i class="fas fa-bars"></i>
                    </span>
                </button>

                <div class="collapse navbar-collapse" id="navbarContent">
                    <!-- Center Nav Items -->
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/') }}">Home</a>
                        </li>
                        @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('event/show') }}">Events</a>
                        </li>
                        @endauth
                        {{-- Add dropdowns if needed --}}
                        {{-- 
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">More</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Option 1</a></li>
                            </ul>
                        </li>
                        --}}
                    </ul>

                    <!-- Right Side -->
                    <ul class="navbar-nav ms-auto align-items-center">
                        @yield('navbar_welcome')
                        @yield('navbar_home')
                        @guest
                        @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}"><i class="fas fa-user nav-icons"></i></a>
                        </li>
                        @endif
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Main Content Placeholder -->
        <main class="py-5">
            @yield('content')
        </main>
    </div>
<!-- f -->

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

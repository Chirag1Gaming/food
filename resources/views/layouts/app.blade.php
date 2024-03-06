<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    {{-- <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet"> --}}

    <link rel="stylesheet" href="{{ URL::asset('theme/css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('theme/css/style.css')}}">
    <link rel="icon" href="{{ URL::asset('favicon.ico')}}" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('theme/css/font.css')}}">
    <link rel="stylesheet" href="{{ URL::asset('theme/font_awesome/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{ URL::asset('theme/css/toastr.min.css')}}" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link href="{{ URL::asset('theme/css/aos.css')}}" rel="stylesheet">
    <script src="{{ URL::asset('theme/js/jquery.min.js')}}"></script>
    <!-- Scripts -->
    @vite(['resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <!-- <li class="nav-item">
                                    <a class="nav-link text-dark" href="{{ (url()->current() == url('login') || url()->current() == url('register')) ? route('login') : url('admin_login') }}">{{ __('Login') }}</a>
                                </li> -->
                            @endif

                            @if (Route::has('register'))
                                <!-- <li class="nav-item">
                                    <a class="nav-link text-dark" href="{{ (url()->current() == url('login') || url()->current() == url('register')) ? route('register') : url('admin_register') }}">{{ __('Register') }}</a>
                                </li> -->
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link float-right dropdown-toggle text-dark" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <script src="{{ URL::asset('theme/js/jquery.validate.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/additional-methods.min.js" integrity="sha512-XZEy8UQ9rngkxQVugAdOuBRDmJ5N4vCuNXCh8KlniZgDKTvf7zl75QBtaVG1lEhMFe2a2DuA22nZYY+qsI2/xA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ URL::asset('theme/js/toastr.min.js')}}" ></script>
    <script src="{{ URL::asset('theme/js/popper.min.js')}}"></script>
    <script src="{{ URL::asset('theme/js/aos.js')}}"></script>
    <script src="{{ URL::asset('theme/js/bootstrap.min.js')}}"></script>
    <script src="{{ URL::asset('theme/js/jquery.blockUI.js')}}"></script>
    <script>
        AOS.init();
    </script>
</body>
</html>

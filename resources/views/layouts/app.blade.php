<!doctype html >
<html dir="rtl" lang="ar">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!--<link rel="stylesheet" href="https://cdn3.devexpress.com/jslib/21.2.5/css/dx.light.css">
    <script type="text/javascript" src="https://cdn3.devexpress.com/jslib/21.2.5/js/dx.all.js"></script>  -->

    <link rel="stylesheet" href="{{ asset('src/lib/css/dx.light.css') }}">
    <script type="text/javascript" src="{{ asset('src/lib/js/dx.all.js') }}"></script>    

</head>
<body >
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    @if (Auth::check())
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav ">
                        <li class="nav-item active hide-for-it">
                            <a class="nav-link mx-4" href="/">الصفحة الرئيسية</a>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link mx-4" href="/electors">الناخبين</a>
                        </li>
                        <li class="nav-item active hide-for-it">
                            <a class="nav-link mx-4" href="/print">طباعة</a>
                        </li>
                        <li class="nav-item active hide-for-it">
                            <a class="nav-link mx-4" href="/ConcadidatesLists">المرشحين</a>
                        </li>
                
                        <li class="nav-item active hide-for-it">
                            <a class="nav-link mx-4" href="/SortResults">فرز النتائج</a>
                        </li>
                        <li class="nav-item active hide-for-it">
                            <a class="nav-link mx-4" href="/UsersLists">المندوبين</a>
                        </li>
                       
                        <li class="nav-item active hide-for-it">
                            <a class="nav-link mx-4" href=" /getTotalByPen">مجموع الاقتراع في كل قلم</a>
                        </li>
                    </ul>
                    @endif
                    <!--a->
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav m-0">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                          
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
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

    @if (Auth::check() && Auth::user()->position  == 'it')
        <style>
            .hide-for-it{
                display: none !important;
            }
        </style>
    @endif
</body>
</html>

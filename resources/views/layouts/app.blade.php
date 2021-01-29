<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/jquery-ui.min.js"></script>


    <!-- Fonts -->

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
     integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    @yield('linkrel')

    <!-- Styles -->

    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <section class="px-8 py-2 mb-6 mt-2">
            <header class="container mx-auto">
                <h1>
                    <img src="{{asset('images/logo.jpg')}}" style="height: 35px;width: 50px" alt="Tweety">
                </h1>
            </header>
        </section>

        <section class="px-8">

            <main class="container mx-auto">
                <div class="lg:flex justify-between">
                    <div class="lg:w-36">
                        @if(auth()->user())
                        @include('_sidebar-links')
                        @endif
                    </div>


                    {{--    Timeline--}}
                    <div class="lg:flex-1 lg:mx-10 mb-8">
                        @yield('content')
                    </div>

                    @if(auth()->user())
                    <div class="lg:w-1/6 bg-gray-100">
                        @include('_friends-list')
                        @include('_whom-to-follow')
                    </div>
                    @endif
                </div>
            </main>

        </section>
    </div>
</body>
</html>

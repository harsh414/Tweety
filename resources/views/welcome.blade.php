<!DOCTYPE html>
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
{{--    <div style="display: flex">--}}
{{--        <div class="w-50">--}}
{{--            <img src="{{asset('images/welcomeimage.jpg')}}" style=" width:100%;height: 100vh" alt="">--}}
{{--        </div>--}}
{{--        <div class="w-50 ml-4">--}}
{{--            <img src="{{asset('images/logo.jpg')}}"--}}
{{--                 class="rounded rounded-lg mt-12 ml-4" style="height: 60px;width: 80px" alt="Tweety">--}}
{{--            <div style="margin-top: 20vh" class="font-extrabold text-5xl tracking-widest ml-4">--}}
{{--                Happening now--}}
{{--            </div>--}}
{{--            <div style="margin-top: 3.5rem" class="font-extrabold text-3xl tracking-wide ml-4">--}}
{{--                Join Twitter Today.--}}
{{--            </div>--}}

{{--            <div class="ml-4 mt-14 align-items-center">--}}
{{--                <div class="links">--}}
{{--                    @auth--}}
{{--                        <a href="{{ url('/tweets') }}"><button class="bg-blue-400 w-25 shadow rounded-lg px-4 py-2 my-1 text-white lg:mr-1" >Home</button></a>--}}
{{--                    @else--}}
{{--                        <a href="{{ route('register') }}"><button class="bg-white-400 w-25 shadow font-bold rounded-lg px-4 py-2 my-1 text-blue-600 lg:mr-1" >Sign Up</button></a>--}}
{{--                        <br><br><br>--}}
{{--                        <a href="{{ route('login') }}"><button class="bg-blue-400 w-25 shadow rounded-lg px-4 py-2 my-1 text-white lg:mr-1">Login</button></a>--}}

{{--                    @endauth--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
<div class="">
    <div class="row">
        <div class="col-md-4" id="back">
            <img src="{{asset('images/welcomeimage.jpg')}}" style=" width:100%;height: 100vh" alt="">
        </div>
        <div class="col-md-8" style="background: black;opacity: 0.8 ;">
            <img src="{{asset('images/logo.jpg')}}" style=" width:45px;height: 45px;margin-top: 5rem">
            <div class="font-weight-bold" style="font-size: 40px;letter-spacing: 2px; font-family: 'cursive';margin-top: 5rem;color: blue">
                <span style="color: white;font-size: 44px" class="font-extrabold" >Happening Now</span>
            </div>
            <div style="color:white;letter-spacing:2px;margin-top: 3rem;font-size: 24px;font-family: 'cursive'" class="text-3xl tracking-wide ml-4">
                Join Twitter Today
            </div>

            <div class="ml-4 mt-14 align-items-center">
                <div class="links">
                    @auth
                        <a href="{{ url('/tweets') }}"><button class="bg-blue-400 w-25 shadow rounded-lg px-4 py-2 my-1 text-white lg:mr-1" >Home</button></a>
                    @else
                        <a href="{{ route('register') }}"><button class="bg-blue-400 w-25 shadow font-bold text-white rounded-lg px-4 py-2 my-1 text-blue-600 lg:mr-1" >Sign Up</button></a>
                        <br><br><br>
                        <a href="{{ route('login') }}"><button class="bg-dark w-25 shadow font-bold text-black rounded-lg px-4 py-2 my-1 text-white lg:mr-1">Login</button></a>

                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>



    </body>
</html>

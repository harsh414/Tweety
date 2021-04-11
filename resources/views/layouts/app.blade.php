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
{{--    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/jquery-ui.min.js"></script>--}}


    <!-- Fonts -->

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href='https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/ui-lightness/jquery-ui.css' rel='stylesheet'>
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
     integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

{{--    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" ></script>--}}
    <script src= "https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" ></script>
    <style>
        .ui-datepicker .ui-datepicker-title select{color: #000;}
    </style>
@yield('linkrel')

    <!-- Styles -->

    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <section class="px-8 py-2 mt-2" style="margin-bottom: 4rem">
            <header class="container mx-auto">
                <h1 class="lg:fixed ">
                    <a href="tweets"><img src="{{asset('images/logo.jpg')}}"
                                          class="rounded rounded-lg border" style="height: 35px;width: 50px" alt="Tweety"></a>
                </h1>
            </header>
        </section>

        <section class="px-8">

            <main class="container mx-auto">
                <div class="lg:flex justify-between">

                    <div class="lg:w-48 lg:ml-6 md:ml-3 lg:fixed">

                        @if(auth()->user())
                        @include('_sidebar-links')
                        @endif
                    </div>

                    {{--    Timeline--}}
                    <div class="lg:flex-1 lg:mx-10 mb-8 lg:ml-56">
                        @yield('content')
                    </div>

                    @if(auth()->user())
                    <div class="lg:w-1/6 bg-gray-100 lg:mr-6 md:mr-4">
                        @include('_friends-list')
                        @include('_whom-to-follow')
                    </div>
                    @endif
                </div>
            </main>

        </section>
    </div>
@include('_modal')
</body>
</html>

<script type="text/javascript">

    $(function($){ // wait until the DOM is ready
        $("#datepicker").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            yearRange: "-40:+40"
        });
        $("#addShadow li").hover(function (){
            $(this).css('background-color','#ccffff');
        },function (){
            $(this).css('background-color','white');
        });
        $("#more").click(function (){
            $("#logout").css('display','block');
        });

        $("#search_more_users").hover(function (){
            $(this).css('background-color','#4d4dff');
        }, function() {
            $(this).css('background-color','#9999ff');
        });

        $(window).load(function(){
            setTimeout(function(){
                $('#myModal33').modal('show');
            }, 2000);
        });


    });
</script>
@yield('scripts')




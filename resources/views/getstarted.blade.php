@extends('layouts.app')
@section('content')
    <h1 class="font-weight-bold text-center text-2xl mb-4" style="font-family: 'cursive'; letter-spacing:1px">
        <span style="color: blue">C</span>on<span style="color: darkred">n</span>ect <span style="color: blue">W</span>ith <span style="color: blue">U</span>sers
    </h1>

    <input type="text" name="search" id="search" placeholder="Search Users to connect.." class="form-control">
    <div id="search_data">
    @foreach($users as $user)
    <div class="text-center flex justify-content-around mt-6 lg:border shadow-inner p-4">
        {{-- flex item 1--}}
        <img src="{{$user->url}}" alt="" class="shadow rounded-full lg:ml-2"
             style="border:2px solid gray; height: 60px; width: 60px;border-right-color: white; border-bottom-color: white ">

        {{-- flex item 2--}}
        <div class="flex flex-col ml-3">
            <a href="{{route('profile',$user->id)}}" style="text-decoration: none">
            <div class="font-weight-bold" style="color: gray;cursor: pointer">{{$user->name}}</div>
            </a>

            <div class="ml-2 flex mt-1">
                <div><span class="font-weight-bold">{{count($user->tweets)}}</span> Tweets</div>
                &nbsp;<div class="ml-1"><span class="font-weight-bold">{{count($user->retweets)}}</span> Retweets</div>
            </div>
        </div>

        <div class="ml-6">
            <form action="{{ auth()->user()->isFollowing($user) ?  "getstarted/".$user->id."/unfollow" : "getstarted/".$user->id."/follow"}}" method="POST">
                {{@csrf_field()}}
                {{method_field("POST")}}
                <input type="text" id="whom_to_follow_id" value="{{$user->id}}" style="display: none">
                <button type="submit" title="{{auth()->user()->isFollowing($user) ? "Unfollow" : "Follow"}}"
                        value="{{auth()->user()->isFollowing($user) ? "Following" : "Follow"}}" id="follow"
                        class="hover:bg-black rounded-lg rounded-lg p-2 font-bold text-blue-400"
                        style="border:2px solid lightskyblue;outline: none; border-radius: 20px !important;">
                    {{auth()->user()->isFollowing($user) ? "Following" : "Follow"}}
                </button>
            </form>
        </div>
    </div>
    @endforeach
    {{ $users->links() }}
    </div>

@endsection

@section('scripts')
    <script type="text/javascript">
        $(function(){

            function fetch_users(query='')
            {
                $.ajax({
                    url:"{{route('livesearch.action')}}",
                    method:"GET",
                    data: {query: query},
                    dataType:'json',
                    success:function (data){
                        $("#search_data").html(data.table_data);
                    }
                })
            }

            $(document).on('keyup','#search',function (){
                var query= $(this).val();
                fetch_users(query);
            });


        });
    </script>


@endsection


@if(Request::path() != 'getstarted')
<h3 class="font-bold flex text-lg block mb-4 mx-2 mt-2">
    Who to Follow
    <img src="https://image.flaticon.com/icons/png/128/907/907873.png" class="ml-2" style="height: 22px;width: 22px" alt="">
</h3>
<ul class="mb-6">
    @foreach($who_to_follow as $user)
        @if($user->id != auth()->user()->id)
        <li class="mb-4 ml-2">
            <div class="flex items-center text-sm">
                <a href="{{route('profile',$user->id)}}"><img src="{{$user->url}}" alt="" class="rounded-full ml-1 mx-2" style="height: 40px;width: 40px;"></a>
                <a href="{{route('profile',$user->id)}}" style="text-decoration: none;color: black">{{$user->name}}</a>
            </div>
        </li>
        @endif
    @endforeach
    <button class="btn ml-4" style="background-color: #9999ff" id="search_more_users">
        <a href="{{route('getstarted')}}" style="text-decoration: none;color: white">
            More...
        </a>
    </button>
</ul>
@endif

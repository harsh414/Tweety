<h3 class="font-bold text-lg block mb-4 mx-2 mt-2">Who to Follow</h3>
<ul>
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
</ul>

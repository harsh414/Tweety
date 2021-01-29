<h3 class="font-bold text-lg block mb-4 mx-2 mt-2">Who to Follow</h3>
<ul>
    @foreach($who_to_follow as $user)
        @if($user->id != auth()->user()->id)
        <li class="mb-4 ml-2">
            <div class="flex items-center text-sm">
                <img src="{{$user->url}}" alt="" class="rounded-full ml-1 mx-2" style="height: 40px;width: 40px;">
                {{$user->name}}
            </div>
        </li>
        @endif
    @endforeach
</ul>

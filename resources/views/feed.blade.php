<div id="tweetsContent">
@foreach($tweets as $tweet)
<div class="rounded-lg border border-gray-300 shadow py-4 px-8 mt-3">
<div class="flex p-2">
    <div class="flex-shrink-0 mr-2">
        <a href="{{route('profile',$tweet->user_id)}}"><img src="{{$tweet->user->url}}" style="height: 50px;width: 50px;" alt="" class="rounded-full mr-2"></a>
    </div>

    <div class="flex flex-col">
        <div class="flex">
        <h5 class="lg:my-2 font-bold flex">

            <a href="{{route('profile',$tweet->user_id)}}" style="text-decoration:none;color: black">{{$tweet->user->name}}
            </a>
            @if($tweet->user->isVip)
            <img src="{{asset('images/verified.png')}}" class="ml-2" style="height: 15px; width: 15px" alt="jrsf">
            @endif
        </h5>
            <h5 class="lg:my-3 ml-3 text-xs"> {{$tweet->created_at->format('d M Y')}} </h5>
        </div>
        <p class="text-sm">
            {{$tweet->body}}
        </p>
    </div>
</div>
    <div class="lg:ml-7" >
    @if($tweet->ifLikedBy(auth()->user(),$tweet))
        <div  class="flex">
        <span onclick="likeUpdate('{{$tweet->id}}')">
        <img src="{{asset('images/download.png')}}" style="height: 20px;width: 20px" alt="">
        </span> &nbsp;
        {{$tweet->num_likes($tweet)->count()}}
        </div>
    @else
        <div  class="flex">
        <span onclick="likeUpdate('{{$tweet->id}}')">
        <img src="https://static.thenounproject.com/png/734918-200.png" style="height: 20px;width: 20px" alt="">
        </span> &nbsp;
        {{$tweet->num_likes($tweet)->count()}}
        </div>
    @endif
    </div>


{{--        <div class="ml-4"><i class="fa fa-retweet"></i>&nbsp;</div>1--}}
</div>
@endforeach
</div>
{{--<script type="text/javascript">--}}
{{--    function --}}
{{--    alert(id);--}}
{{--</script>--}}

<script src="{{asset('js/likeDislike.js')}}" type="text/javascript"></script>






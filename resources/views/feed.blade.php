{{--@foreach(range(1,7) as $index)--}}
@foreach($tweets as $tweet)
<div class="rounded-lg borde

r border-gray-300 shadow py-4 px-8 mt-3">
<div class="flex p-2">
    <div class="flex-shrink-0 mr-2">
        <a href="{{route('profile',$tweet->user_id)}}"><img src="{{$tweet->user->url}}" style="height: 50px;width: 50px;" alt="" class="rounded-full mr-2"></a>
    </div>

    <div class="">
        <h5 class="lg:my-2 font-bold">
            {{$tweet->user->name}}
        </h5>
        <p class="text-sm">
            {{$tweet->body}}
        </p>
    </div>
</div>
    <div class="flex lg:ml-16">
        <div class="flex-1"><i class="fa fa-comment"></i> 1</div>
        <div class="flex-1"><i class="fa fa-heart"></i> 1</div>
        <div class="flex-1"><i class="fa fa-retweet"></i> 1</div>
        <div class="flex-1">1</div>
    </div>
</div>
@endforeach


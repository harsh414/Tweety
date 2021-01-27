{{--@foreach(range(1,7) as $index)--}}
@foreach($tweets as $tweet)
<div class="rounded-lg border border-gray-300 shadow py-4 px-8 mt-3">
<div class="flex p-2">
    <div class="flex-shrink-0 mr-2">
        <img src="https://i.pravatar.cc/50" alt="" class="rounded-full mr-2">
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


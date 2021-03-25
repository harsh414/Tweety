@foreach($tweets as $tweet)
    <div class="rounded-lg border border-gray-300 shadow py-4 px-8 mt-3">
        @if(array_key_exists($tweet->id,$twodarray))
            <div class="flex mb-3">
                <svg viewBox="0 0 24 24" style="height: 18px;width: 18px" class="r-m0bqgq r-4qtqp9 r-yyyyoo r-z80fyv r-dnmrzs r-bnwqim r-1plcrui r-lrvibr r-19wmn03"><g><path d="M23.615 15.477c-.47-.47-1.23-.47-1.697 0l-1.326 1.326V7.4c0-2.178-1.772-3.95-3.95-3.95h-5.2c-.663 0-1.2.538-1.2 1.2s.537 1.2 1.2 1.2h5.2c.854 0 1.55.695 1.55 1.55v9.403l-1.326-1.326c-.47-.47-1.23-.47-1.697 0s-.47 1.23 0 1.697l3.374 3.375c.234.233.542.35.85.35s.613-.116.848-.35l3.375-3.376c.467-.47.467-1.23-.002-1.697zM12.562 18.5h-5.2c-.854 0-1.55-.695-1.55-1.55V7.547l1.326 1.326c.234.235.542.352.848.352s.614-.117.85-.352c.468-.47.468-1.23 0-1.697L5.46 3.8c-.47-.468-1.23-.468-1.697 0L.388 7.177c-.47.47-.47 1.23 0 1.697s1.23.47 1.697 0L3.41 7.547v9.403c0 2.178 1.773 3.95 3.95 3.95h5.2c.664 0 1.2-.538 1.2-1.2s-.535-1.2-1.198-1.2z"></path></g></svg>
                <div class="text-sm ml-3 font-weight-bold" style="color: #993366">{{$twodarray[$tweet->id]}} Retweeted</div>
            </div>
        @endif
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
                            <img src="{{asset('images/verified.png')}}" class="ml-2" style="height: 15px; width: 15px" alt="verified">
                        @endif
                    </h5>
                    <h5 class="lg:my-3 ml-3 text-xs"> {{$tweet->created_at->format('d M Y')}} </h5>
                </div>
                <p class="text-sm">
                {{$tweet->body}}

                @if($tweet->mediaurl != "")
                    @if($tweet->mediaformat == 'png' || $tweet->mediaformat=='jpg')
                        <div style="width: auto;max-height: 300px;box-shadow: 4px 5px 7px 3px lightgray" class="mt-4 mb-3 lg:ml-6 lg:mr-6">
                            <img src="{{$tweet->mediaurl}}" style="width:100vw; max-height: 300px;" class="rounded"/>
                        </div>
                    @elseif($tweet->mediaformat == 'mp4')
                        <div style="width: auto;max-height: 300px;box-shadow: 4px 5px 7px 3px lightgray;" class="mt-4 mb-3 lg:ml-6 lg:mr-6">
                            <video style="width:auto; max-height: 300px;border: 4px inset lightgray;" controls>
                                <source src="{{$tweet->mediaurl}}" type="video/mp4">
                            </video>
                        </div>
                        @endif
                        @endif
                        </p>
            </div>
        </div>
        <div class="lg:ml-7 mt-3 flex justify-content-around" id="refLikes{{$tweet->id}}">
        @if($tweet->ifLikedBy(auth()->user(),$tweet))
            <div class="flex">
            <span onclick="likeUpdate('{{$tweet->id}}')">
            <img src="{{asset('images/download.png')}}" style="height: 20px;width: 20px" alt="">
            </span> &nbsp;
            {{$tweet->num_likes($tweet)->count()}}
            </div>
        @else
            <div class="flex">
            <span onclick="likeUpdate('{{$tweet->id}}')">
            <img src="https://static.thenounproject.com/png/734918-200.png" style="height: 20px;width: 20px" alt="">
            </span> &nbsp;
            {{$tweet->num_likes($tweet)->count()}}
            </div>
        @endif

        <div class="flex">
            <span onclick="tweetUpdate('{{$tweet->id}}')"><svg viewBox="0 0 24 24" fill="red" style="height: 18px;width: 18px;" class="r-m0bqgq r-4qtqp9 r-yyyyoo r-z80fyv r-dnmrzs r-bnwqim r-1plcrui r-lrvibr r-19wmn03"><g><path d="M23.615 15.477c-.47-.47-1.23-.47-1.697 0l-1.326 1.326V7.4c0-2.178-1.772-3.95-3.95-3.95h-5.2c-.663 0-1.2.538-1.2 1.2s.537 1.2 1.2 1.2h5.2c.854 0 1.55.695 1.55 1.55v9.403l-1.326-1.326c-.47-.47-1.23-.47-1.697 0s-.47 1.23 0 1.697l3.374 3.375c.234.233.542.35.85.35s.613-.116.848-.35l3.375-3.376c.467-.47.467-1.23-.002-1.697zM12.562 18.5h-5.2c-.854 0-1.55-.695-1.55-1.55V7.547l1.326 1.326c.234.235.542.352.848.352s.614-.117.85-.352c.468-.47.468-1.23 0-1.697L5.46 3.8c-.47-.468-1.23-.468-1.697 0L.388 7.177c-.47.47-.47 1.23 0 1.697s1.23.47 1.697 0L3.41 7.547v9.403c0 2.178 1.773 3.95 3.95 3.95h5.2c.664 0 1.2-.538 1.2-1.2s-.535-1.2-1.198-1.2z"></path></g></svg></span>
            <span class="pb-2 ml-2" style="color: red">{{$tweet->retweets()->count()}}</span>
        </div>
        </div>
    </div>
@endforeach


<script src="{{asset('js/feedActivity.js')}}" type="text/javascript"></script>








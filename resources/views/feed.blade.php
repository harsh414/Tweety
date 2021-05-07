@foreach($tweets as $tweet)
    <div class="rounded-lg border border-gray-300 shadow py-4 px-8 mt-3">
        @if(array_key_exists($tweet->id,$twodarray))
            <div class="flex mb-3">
                <svg viewBox="0 0 24 24" style="height: 18px;width: 18px" class="r-m0bqgq r-4qtqp9 r-yyyyoo r-z80fyv r-dnmrzs r-bnwqim r-1plcrui r-lrvibr r-19wmn03"><g><path d="M23.615 15.477c-.47-.47-1.23-.47-1.697 0l-1.326 1.326V7.4c0-2.178-1.772-3.95-3.95-3.95h-5.2c-.663 0-1.2.538-1.2 1.2s.537 1.2 1.2 1.2h5.2c.854 0 1.55.695 1.55 1.55v9.403l-1.326-1.326c-.47-.47-1.23-.47-1.697 0s-.47 1.23 0 1.697l3.374 3.375c.234.233.542.35.85.35s.613-.116.848-.35l3.375-3.376c.467-.47.467-1.23-.002-1.697zM12.562 18.5h-5.2c-.854 0-1.55-.695-1.55-1.55V7.547l1.326 1.326c.234.235.542.352.848.352s.614-.117.85-.352c.468-.47.468-1.23 0-1.697L5.46 3.8c-.47-.468-1.23-.468-1.697 0L.388 7.177c-.47.47-.47 1.23 0 1.697s1.23.47 1.697 0L3.41 7.547v9.403c0 2.178 1.773 3.95 3.95 3.95h5.2c.664 0 1.2-.538 1.2-1.2s-.535-1.2-1.198-1.2z"></path></g></svg>
                <div class="text-sm ml-3 font-weight-bold" style="color: #993366">
                    @if($twodarray[$tweet->id]== auth()->user()->name)
                        @if($tweet->isRetweeted(auth()->user(),$tweet))
                        You Retweeted
                        @endif
                    @else
                        {{$twodarray[$tweet->id]}} Retweeted
                    @endif
                </div>
            </div>
        @endif
        <div class="flex p-2">
            <div class="flex-shrink-0 mr-2" id="image_hover_modal">
{{--                <a href="{{route('profile',$tweet->user_id)}}">--}}
                    <img src="{{$tweet->user->url}}" id="{{$tweet->user->id}}" style="height: 50px;width: 50px;" alt="" class="rounded-full mr-2">
{{--                </a>--}}
            </div>
            <div class="flex flex-col">
                <div class="flex">
                    <h5 class="lg:my-2 font-bold flex">
                        <a href="{{route('profile',$tweet->user_id)}}" class="mr-1" style="text-decoration:none;color: black">{{$tweet->user->name}}
                        </a>
                        @if($tweet->user->isVip)
                            <svg viewBox="0 0 24 24" style="height:18px;width:18px" fill="#d24dff" aria-label="Verified account" class="r-13gxpu9 r-4qtqp9 r-yyyyoo r-1xvli5t r-9cviqr r-dnmrzs r-bnwqim r-1plcrui r-lrvibr"><g><path d="M22.5 12.5c0-1.58-.875-2.95-2.148-3.6.154-.435.238-.905.238-1.4 0-2.21-1.71-3.998-3.818-3.998-.47 0-.92.084-1.336.25C14.818 2.415 13.51 1.5 12 1.5s-2.816.917-3.437 2.25c-.415-.165-.866-.25-1.336-.25-2.11 0-3.818 1.79-3.818 4 0 .494.083.964.237 1.4-1.272.65-2.147 2.018-2.147 3.6 0 1.495.782 2.798 1.942 3.486-.02.17-.032.34-.032.514 0 2.21 1.708 4 3.818 4 .47 0 .92-.086 1.335-.25.62 1.334 1.926 2.25 3.437 2.25 1.512 0 2.818-.916 3.437-2.25.415.163.865.248 1.336.248 2.11 0 3.818-1.79 3.818-4 0-.174-.012-.344-.033-.513 1.158-.687 1.943-1.99 1.943-3.484zm-6.616-3.334l-4.334 6.5c-.145.217-.382.334-.625.334-.143 0-.288-.04-.416-.126l-.115-.094-2.415-2.415c-.293-.293-.293-.768 0-1.06s.768-.294 1.06 0l1.77 1.767 3.825-5.74c.23-.345.696-.436 1.04-.207.346.23.44.696.21 1.04z"></path></g></svg>
                        @endif
                    </h5>
                    <h5 class="lg:my-3 ml-3 text-xs"> {{$tweet->created_at->format('d M Y')}} </h5>
                </div>
                <p class="text-sm">
                {{$tweet->body}}

                @if($tweet->mediaurl != "")
                    @if($tweet->mediaformat == 'png' || $tweet->mediaformat=='jpg' || $tweet->mediaformat=='gif')
                        <div style="width: 75%;max-height: 300px;box-shadow: 4px 5px 7px 3px lightgray;align-self: center"  class="mt-4 mb-3 text-center">
                            <img src="{{$tweet->mediaurl}}" style="width: 100vw;max-height: 300px;" class="rounded"/>
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
            @if($tweet->isRetweeted(auth()->user(),$tweet))
            <span onclick="tweetUpdate('{{$tweet->id}}')"  data-toggle="tooltip" title="Undo Retweet"><svg viewBox="0 0 24 24" stroke="light-green" fill="red" style="height: 18px;width: 18px;" class="r-m0bqgq r-4qtqp9 r-yyyyoo r-z80fyv r-dnmrzs r-bnwqim r-1plcrui r-lrvibr r-19wmn03"><g><path d="M23.615 15.477c-.47-.47-1.23-.47-1.697 0l-1.326 1.326V7.4c0-2.178-1.772-3.95-3.95-3.95h-5.2c-.663 0-1.2.538-1.2 1.2s.537 1.2 1.2 1.2h5.2c.854 0 1.55.695 1.55 1.55v9.403l-1.326-1.326c-.47-.47-1.23-.47-1.697 0s-.47 1.23 0 1.697l3.374 3.375c.234.233.542.35.85.35s.613-.116.848-.35l3.375-3.376c.467-.47.467-1.23-.002-1.697zM12.562 18.5h-5.2c-.854 0-1.55-.695-1.55-1.55V7.547l1.326 1.326c.234.235.542.352.848.352s.614-.117.85-.352c.468-.47.468-1.23 0-1.697L5.46 3.8c-.47-.468-1.23-.468-1.697 0L.388 7.177c-.47.47-.47 1.23 0 1.697s1.23.47 1.697 0L3.41 7.547v9.403c0 2.178 1.773 3.95 3.95 3.95h5.2c.664 0 1.2-.538 1.2-1.2s-.535-1.2-1.198-1.2z"></path></g></svg></span>
            <span class="pb-2 ml-2" style="color: red">{{$tweet->retweets()->count()}}</span>
            @else
            <span onclick="tweetUpdate('{{$tweet->id}}')"  data-toggle="tooltip"  title="Retweet"><svg viewBox="0 0 24 24" fill="" style="height: 18px;width: 18px;" class="r-m0bqgq r-4qtqp9 r-yyyyoo r-z80fyv r-dnmrzs r-bnwqim r-1plcrui r-lrvibr r-19wmn03"><g><path d="M23.615 15.477c-.47-.47-1.23-.47-1.697 0l-1.326 1.326V7.4c0-2.178-1.772-3.95-3.95-3.95h-5.2c-.663 0-1.2.538-1.2 1.2s.537 1.2 1.2 1.2h5.2c.854 0 1.55.695 1.55 1.55v9.403l-1.326-1.326c-.47-.47-1.23-.47-1.697 0s-.47 1.23 0 1.697l3.374 3.375c.234.233.542.35.85.35s.613-.116.848-.35l3.375-3.376c.467-.47.467-1.23-.002-1.697zM12.562 18.5h-5.2c-.854 0-1.55-.695-1.55-1.55V7.547l1.326 1.326c.234.235.542.352.848.352s.614-.117.85-.352c.468-.47.468-1.23 0-1.697L5.46 3.8c-.47-.468-1.23-.468-1.697 0L.388 7.177c-.47.47-.47 1.23 0 1.697s1.23.47 1.697 0L3.41 7.547v9.403c0 2.178 1.773 3.95 3.95 3.95h5.2c.664 0 1.2-.538 1.2-1.2s-.535-1.2-1.198-1.2z"></path></g></svg></span>
            <span class="pb-2 ml-2" style="color: black">{{$tweet->retweets()->count()}}</span>
            @endif

        </div>
        </div>
    </div>
@endforeach
<script src="{{asset('js/feedActivity.js')}}" type="text/javascript"></script>












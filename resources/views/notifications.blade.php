@extends('layouts.app')

@section('content')
    <h1 class="font-weight-bolder mb-5 text-xl" style="letter-spacing: 1px">Notifications</h1>
    <hr class="mb-2 mt-2">
    @foreach(auth()->user()->notifications as $notification)
        @if($notification->data['type']=='Registration')
            <div id="notificationBg" class="pt-1" style="cursor: pointer;background-color: #e6ffff">
                <div class="flex mt-6">
                    <svg viewBox="0 0 24 24" style="height: 24px;width: 24px;align-self: center" class="r-4qtqp9 r-yyyyoo r-yucp9h r-dnmrzs r-bnwqim r-1plcrui r-lrvibr"><g><path d="M22.99 11.295l-6.986-2.13-.877-.326-.325-.88L12.67.975c-.092-.303-.372-.51-.688-.51-.316 0-.596.207-.688.51l-2.392 7.84-1.774.657-6.148 1.82c-.306.092-.515.372-.515.69 0 .32.21.6.515.69l7.956 2.358 2.356 7.956c.09.306.37.515.69.515.32 0 .6-.21.69-.514l1.822-6.15.656-1.773 7.84-2.392c.303-.09.51-.37.51-.687 0-.316-.207-.596-.51-.688z" fill="#794BC4"></path></g></svg>
                    <img src="{{auth()->user()->url}}" style="height: 40px;width: 40px;" alt="" class="rounded-full ml-3">
                </div>
                <div class="mt-3 ml-4" style="color: darkcyan !important;">
                    {{$notification->data['message']}}
                </div>
            </div>



        @elseif($notification->data['type']=='New tweet')
            <div id="notificationBg" class="pt-1" style="cursor: pointer;background-color: #e6ffff;">
                <a href="{{route('tweetshow',$notification->data['id'])}}" style="text-decoration: none;color: black">
                <div class="flex mt-6">
                <svg viewBox="0 0 24 24" style="height: 24px;width: 24px;align-self: center" class="r-4qtqp9 r-yyyyoo r-yucp9h r-dnmrzs r-bnwqim r-1plcrui r-lrvibr"><g><path d="M22.99 11.295l-6.986-2.13-.877-.326-.325-.88L12.67.975c-.092-.303-.372-.51-.688-.51-.316 0-.596.207-.688.51l-2.392 7.84-1.774.657-6.148 1.82c-.306.092-.515.372-.515.69 0 .32.21.6.515.69l7.956 2.358 2.356 7.956c.09.306.37.515.69.515.32 0 .6-.21.69-.514l1.822-6.15.656-1.773 7.84-2.392c.303-.09.51-.37.51-.687 0-.316-.207-.596-.51-.688z" fill="#794BC4"></path></g></svg>
                <img src="{{$notification->data['url']}}" style="height: 40px;width: 40px;" alt="" class="rounded-full ml-3">
            </div>

            <div class="mt-3 ml-4">
                {{$notification->data['message']}}
            </div>

            <div class="mt-3 ml-4" style="color: darkcyan !important;">
                {{$notification->data['tweet_body']}}
            </div>
                </a>
            </div>


        @elseif($notification->data['type']=='newFollowing')
            <div id="notificationBg" class="pt-1" style="background-color: #e6ffff;">
                <a href="{{route('profile',$notification->data['id'])}}" style="text-decoration: none;color: black;cursor: pointer">
            <div class="flex mt-6">
                <svg viewBox="0 0 24 24" style="height: 24px;width: 24px;align-self: center" fill="purple" class="r-13gxpu9 r-4qtqp9 r-yyyyoo r-yucp9h r-dnmrzs r-bnwqim r-1plcrui r-lrvibr"><g><path d="M12.225 12.165c-1.356 0-2.872-.15-3.84-1.256-.814-.93-1.077-2.368-.805-4.392.38-2.826 2.116-4.513 4.646-4.513s4.267 1.687 4.646 4.513c.272 2.024.008 3.46-.806 4.392-.97 1.106-2.485 1.255-3.84 1.255zm5.849 9.85H6.376c-.663 0-1.25-.28-1.65-.786-.422-.534-.576-1.27-.41-1.968.834-3.53 4.086-5.997 7.908-5.997s7.074 2.466 7.91 5.997c.164.698.01 1.434-.412 1.967-.4.505-.985.785-1.648.785z"></path></g></svg>
                <img src="{{$notification->data['url']}}" style="height: 40px;width: 40px;" alt="" class="rounded-full ml-3">
            </div>

            <div class="mt-3 ml-4" style="color: darkcyan !important;">
                {{$notification->data['message']}}
            </div>
                </a>
            </div>
        @endif
        <hr class="mb-2 mt-2">
    @endforeach
@endsection

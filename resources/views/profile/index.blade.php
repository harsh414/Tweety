@extends('layouts.app')
{{--getting $profile from profileControlelr--}}

@section('content')
<div class="flex header lg:pl-2 lg:border shadow-inner">
    <a href="{{route('tweets')}}"><img src="{{asset('images/iconback.png')}}"
         class="mr-2 mt-1" style="height: 20px; width: 20px" alt="back">
    </a>
    <div class="flex flex-col">
        <div class="font-bold font-medium">{{$profile->name}}</div>
        <div class="text-sm">{{count($profile->tweets)}} Tweets</div>
    </div>

</div>
<div class="bg-gray-300" style="height: 150px;">
</div>

{{--adding a modal here for viewing image in modal--}}

<img src="{{$profile->url}}" alt="" class="shadow pr-1 rounded-full lg:ml-2" style="margin-top: -4rem;
border:3px solid gray; height: 150px; width: 150px;
border-right-color: white; border-bottom-color: white " data-toggle="modal" data-target="#imagePreview">
<div class="flex justify-between">
    <div class="mt-1 ml-1 font-bold text-lg">{{$profile->name}}</div>
    <p class="text-gray-500 font-bold shadow pt-1.5" id="success" style="display: block">
        @if(session('message'))
            {{session('message')}}
        @endif
        @foreach($errors->all() as $error)
            {{$error}}
        @endforeach
    </p>

{{--show edit button if profile is auth() users profile else show follow button--}}
    @if($profile->id == auth()->user()->id)
    <button type="button" id="editProfile"
    class="hover:bg-black rounded-lg rounded-lg p-2 font-bold text-blue-400" data-toggle="modal" data-target="#exampleModal" style="border:2px solid lightskyblue;outline: none; border-radius: 20px !important;">Edit profile</button>
    @else

    <form action="{{ $status=='Follow' ?  $profile->id."/follow" : $profile->id."/unfollow"}}" method="POST">
    {{@csrf_field()}}
    {{method_field("POST")}}
    <input type="text" id="whom_to_follow_id" value="{{$profile->id}}" style="display: none">
    <button type="submit" title="{{$status=='Following' ? "Unfollow" : "Follow"}}" value="{{$status}}" id="follow"
    class="hover:bg-black rounded-lg rounded-lg p-2 font-bold text-blue-400"
    style="border:2px solid lightskyblue;outline: none; border-radius: 20px !important;">
    {{$status}}
    </button>
    </form>
    @endif
</div>


<div class="flex gap-2">
    <img src="{{asset('images/calendar.png')}}" style="height: 20px; width: 20px" alt="">
    <div>Joined {{ $profile->created_at->format('M Y') }}</div>
</div>
<div class="flex gap-5">
    <div class="text-sm text-gray-500"><span class="font-bold text-black">{{count($profile->following)}}</span> Following</div>
    <div class="text-sm text-gray-500"><span class="font-bold text-black">{{$followers}}</span> Followers</div>
</div>


<ul class="mt-4 flex justify-between" id="friends">
    <li style="display: none" class="getuser" id="{{$profile->id}}"></li>
    <li class=""><a style="cursor: pointer" class="tabc" id="tweets_tab" data-toggle="tabajax"> Tweets & Retweets</a></li>
    <li class=""><a style="cursor: pointer" class="tabc"  id="media_tab"  data-toggle="tabajax"> Media</a></li>
    <li class=""><a style="cursor: pointer" class="tabc" id="likes_tab"  data-toggle="tabajax">Likes</a></li>
</ul>



<div class="mt-4" id="content">

</div>


<!-- Modal -->
<form action="{{$profile->id}}" method="POST" enctype="multipart/form-data">
    {{@csrf_field()}}
    {{ method_field('PUT') }}
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="" id="exampleModalLabel">Edit Profile</h5>
                <button type="submit" id="UpdateProfile" class="btn btn-primary">Save</button>
            </div>
            <div class="">
                <div class="bg-gray-300" style="height: 90px"></div>
            </div>
            <div class="">
                <img src="{{$profile->url}}" id="profile_img" alt="" class="shadow pr-1 rounded-full lg:ml-2"
                 style="margin-top: -4rem;border:3px solid gray; height: 100px;width: 100px;
                border-right-color: white;
                border-bottom-color: white">
            </div>

            <input type="file" name="file" class="mt-2 ml-2"
                   onchange="document.getElementById('profile_img').src = window.URL.createObjectURL(this.files[0])">
            <div class="modal-body">
            <div class="mt-1 border border-gray-50">
            <h3 class="text-sm lg:mt-1">Name</h3>
                <textarea name="name" class="w-full px-4 py-2" id="name" placeholder="{{$profile->name}}" style="outline: none">
                    {{$profile->name}}
                </textarea>
            </div>

            <div class="mt-1 border border-gray-100">
                <h3 class="text-sm lg:ml-1">Bio</h3>
                <textarea name="bio" id="bio" class="w-full px-4 py-2" placeholder="{{$profile->bio}}" style="outline: none">

                </textarea>
            </div>
             <div>
                 <h3 class="text-sm lg:ml-1">Birth date</h3>
                 <input type="" style="outline: none" id="datepicker" name="dob" class="border border-gray-500 pl-2 text-sm">
             </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
</form>


{{--Modal for image preview--}}
<div class="modal fade" id="imagePreview" tabindex="-1" role="dialog" aria-labelledby="imagePreviewLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-body">
                <img src="{{$profile->url}}" style="width: 100vw;height: auto" alt="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


@section('scripts')
<script src="{{asset('js/profilescriptt.js')}}"></script>
<script src="{{asset('js/profileActivity.js')}}" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function (){
        setTimeout(function (){
            $("#success").fadeOut();
        },3000);

        $("#follow").tooltip();
        $("#follow").hover(function (){
            $(this).removeClass("text-blue-400");
            $(this).addClass("text-white");
        },function (){
            $(this).removeClass("text-white");
            $(this).addClass("text-blue-400");
        });

        $("#editProfile").hover(function (){
            $(this).removeClass("text-blue-400");
            $(this).addClass("text-white");
        },function (){
            $(this).removeClass("text-white");
            $(this).addClass("text-blue-400");
        });


    })
</script>
@endsection

@endsection
{{--<script src="{{asset('js/profileLikeDislike.js')}}" type="text/javascript"></script>--}}

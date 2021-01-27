@extends('layouts.app')

@section('content')
<div class="lg:flex justify-between">
    <div class="lg:w-36">
        @include('_sidebar-links')
    </div>


{{--    Timeline--}}
    <div class="lg:flex-1 lg:mx-10 mb-8">
        @include('_newTweet')
        @include('feed')
    </div>


    <div class="lg:w-1/6 bg-gray-200">
        @include('_friends-list')
        @include('_whom-to-follow')
    </div>
</div>
@endsection

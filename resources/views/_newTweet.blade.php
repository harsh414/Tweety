<div class="rounded-lg border shadow-inner border-gray-400 py-4 px-8">
    <form method="POST" action="{{route('tweet.store')}}">
        {{@csrf_field()}}
                <textarea name="body" class="w-full px-4 py-3" placeholder="What's happening :) ?" style="outline: none">

                </textarea>
        <hr class="py-2">

        <footer class="flex justify-between">
            <img src="https://i.pravatar.cc/50" alt="" class="rounded-full mr-2">
            <button type="submit" class="bg-blue-400 shadow rounded-lg px-4 py-2 my-1 text-white lg:mr-1">
                Tweet
            </button>
        </footer>

    </form>
    @foreach($errors->all() as $error)
        <p class="text-red-500 font-bold" id="error" style="display: block">
            {{$error}}
        </p>
    @endforeach

{{--    setTimeout on error--}}
    @include('error')
</div>

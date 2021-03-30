<ul>
    <li class="">
        <a class="font-bold text-lg mb-4 block" id="addShadow" style="text-decoration: none" href="{{route('tweets')}}">
        Home
        </a>
    </li>
    <li>
        <a class="font-bold text-lg mb-4 block" id="addShadow" href="" style="text-decoration: none">
            Explore
        </a>
    </li>
    <li>
        <a class="font-bold text-lg mb-4 block" id="addShadow" href="{{route('notifications')}}" style="text-decoration: none">
            Notifications <span class="rounded-lg pl-1 pr-1" style="background: orange">
                {{auth()->user()->unreadNotifications->count()}}</span>
        </a>
    </li>
    <li>

        <a class="font-bold text-lg mb-4 block" id="addShadow" href="" style="text-decoration: none">
            Messages
        </a>
    </li>
    <li id="addShadow">
        <a class="font-bold text-lg mb-4 block" id="addShadow" href="" style="text-decoration: none">
            Bookmarks
        </a>
    </li>
    <li>
        <a class="font-bold text-lg mb-4 block" id="addShadow" href="" style="text-decoration: none">
            Lists
        </a>
    </li>
    <li>
        <a class="font-bold text-lg mb-4 block" id="addShadow" href="{{route('profile',auth()->user()->id)}}" style="text-decoration: none">
            Profile
        </a>
    </li>
    <li>
        <a class="font-bold text-lg mb-4 block" id="addShadow" href="" style="text-decoration: none">
            More
        </a>
    </li>
    <li>


            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="">
                @csrf
                <button type="submit">
                    <div class="bg-blue-400 shadow rounded-lg px-4 py-2 my-1 text-white lg:mr-1">
                        Logout
                    </div>
                </button>
            </form>
    </li>
    <br>

</ul>

<script type="text/javascript">
    function color(){

    }
</script>

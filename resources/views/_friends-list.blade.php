<h3 class="font-bold block mb-4 mx-2 mt-2">Friends</h3>
<hr>
<ul>
    @foreach(range(1,7) as $index)
        <li class="mb-4 ml-2">
            <div class="flex items-center text-sm">
                <img src="https://i.pravatar.cc/40" alt="" class="rounded-full mr-2">
                John Doe
            </div>
        </li>
    @endforeach
</ul>

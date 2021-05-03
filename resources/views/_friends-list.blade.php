<h3 class="font-bold flex text-lg block mb-4 mx-2 mt-2" style="letter-spacing: 1px;font-family: cursive">
    Following
    <img src="https://static.thenounproject.com/png/2712011-200.png"
         class="ml-2 mt-0" style="height: 25px;width: 25px;" alt="">
</h3>
<ul>
    @foreach(auth()->user()->following as $user)
        <li class="mb-4 ml-2 mt-2">
            <div class="flex items-center text-sm" id="image_hover_modal">
{{--                <a href="{{route('profile',$user->id)}}">--}}
                    <img src="{{$user->url}}" id="{{$user->id}}"  alt="" class="rounded-full ml-1 mx-2" style="height: 40px;width: 40px;">
{{--                </a>--}}
                <a href="{{route('profile',$user->id)}}" style="text-decoration: none;color: black">
                    {{strlen($user->name) > 18 ? substr($user->name,0,15)."..." : $user->name}}
                </a>
            </div>
        </li>
    @endforeach
</ul>
<hr>

@section('scripts')
    <script type="text/javascript">
        $(function (){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $("#image_hover_modal img").hover(function (){
                var timeout;
                var id= $(this).attr('id');
                var location= window.location.pathname.split('/')[3];
                $.ajax({
                    type:'POST',
                    url: location+"/hoverUser",
                    data:{id:id,location:location},
                    dataType:"json",
                    success:function(data){
                        $("#modal_content").html(data.data);
                        $("#display_hover_image").trigger('click');
                    }
                });

            },function (){

            });
        });
    </script>
@endsection

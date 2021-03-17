<div class="rounded-lg border shadow-inner border-gray-400 py-4 px-8">
    <form method="POST" action="{{route('tweet.store')}}" enctype="multipart/form-data">
        {{@csrf_field()}}
        <textarea name="body" class="w-full px-4 py-3" placeholder="What's happening :) ?" style="outline: none">

        </textarea>

        <div style="width: 100%;max-height: 400px;">
        <img id="preview" style="width:100%; max-height: 400px; content: none;"/>
        </div>


        <input type="file" accept="jpg|png|mp4" name="upload"  onchange="document.getElementById('preview').src = window.URL.createObjectURL(this.files[0])" id="upload" style="display: none">
        <button type="button" id="check"  style="margin-left: 1rem;outline: none">
            <img src="https://image.flaticon.com/icons/png/128/1040/1040241.png" style="width: 28px;height: 28px" alt="">
        </button>


        <hr class="py-2">

        <footer class="flex justify-between">
            <img src="{{auth()->user()->url}}" alt="" class="rounded-full mr-2" style="height: 50px;width: 50px;" data-toggle="modal" data-target="#imagePreview">
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

<div class="modal fade" id="imagePreview" tabindex="-1" role="dialog" aria-labelledby="imagePreviewLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-body">
                <img src="{{auth()->user()->url}}" style="width: 100vw;height: auto" alt="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function (){
        $("#check").click(function (){
            $("#upload").click();
        })
    })
</script>

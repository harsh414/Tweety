function likeUpdate(id) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type:'POST',
        url: "tweets/likeOrDislike",
        // data:{_token: '{{ csrf_token() }}'},  way to pass csrf token
        data:{t_id:id},
        dataType:"json",
        success:function(data){
            $("#refLikes"+id).html(data.activityData);
        }
    });
}

function tweetUpdate(id){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type:'POST',
        url: "tweets/retweet",
        data:{t_id:id},
        dataType:"json",
        success:function(data){
            $("#refLikes"+id).html(data.activityData);
        }
    });
}

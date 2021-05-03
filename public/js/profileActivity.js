function likeUpdate(id) {
    // $.ajaxSetup({
    //     headers: {
    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //     }
    // });

    $.ajax({
        type:'POST',
        url: "{id}/likeOrDislike",
        data:{_token: "{{ csrf_token() }}",t_id:id},
        dataType:"json",
        success:function(data){
            $("#refLikes"+id).html(data.activityData);
        }
    });
}

function tweetUpdate(id){
    $.ajax({
        type:'POST',
        url: "{id}/retweet",
        data:{_token: "{{ csrf_token() }}",t_id:id},
        dataType:"json",
        success:function(data){
            $("#refLikes"+id).html(data.activityData);
        }
    });
}




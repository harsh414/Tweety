function likeUpdate(id) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type:'POST',
        url: "tweets/likeOrDislike",
        data:{t_id:id},
        dataType:"json",
        success:function(data){
            $("#refLikes"+id).html(data.likeData);
        }
    });
}



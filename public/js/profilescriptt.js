$(document).ready(function (){

    $('.tabc').click(function(e) {

        $('.tabc').parent().removeClass("font-bold text-blue-500");
        $(this).parent().addClass("font-bold text-blue-500");

        var tabId= $(this).attr('id');
        var id = ($('.getuser').attr('id'));

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if(tabId == 'tweets_tab'){
            $("#content").empty();
            $.ajax({
                type:'POST',
                url: "{{$profile->id}}/tweets",
                data:{id:id},
                dataType:'json',
                success:function(data){
                    $("#content").html(data.table_data);
                }
            });

        }

        if(tabId=='retweets_tab'){
            $("#content").empty();
            $.ajax({
                type:'POST',
                url: "{{$profile->id}}/getRetweets",
                data:{id:id},
                dataType:'json',
                success:function(data){
                    $("#content").html(data.table_data);
                }
            });
        }

        if(tabId == 'media_tab'){
            $("#content").empty();
            // ...logic here
            $.ajax({
                type:'POST',
                url: "{{$profile->id}}/media",
                data:{id:id},
                dataType:'json',
                success:function(data){
                    $("#content").html(data.table_data);
                }
            });
        }

        if(tabId == 'likes_tab'){

            $("#content").empty();
            $.ajax({
                type:'POST',
                url: "{{$profile->id}}/likes",
                data:{id:id},
                dataType:'json',
                success:function(data){
                    $("#content").html(data.table_data);
                }
            });
        }


    });
    $("#tweets_tab").trigger("click");

    setTimeout(function (){
        $("#success").fadeOut();
    },3000);

});

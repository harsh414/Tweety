@if($errors->any())
    <script type="text/javascript">
        $(document).ready(function (){
            setTimeout(function (){
                $("#error").fadeOut();
            },3000);
        });
    </script>
@endif

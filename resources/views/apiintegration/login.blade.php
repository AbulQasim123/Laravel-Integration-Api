@include('apiintegration.header')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h4 align="center" class="my-4">Laravel API Integration</h4>
            <div class="alert alert-danger alert-dismissible" id="result" style="display: none;"></div>
            <div class="form">
                <h4 align="center">Login</h4>
                <h3 id="loggedout"></h3>
                <form id="login_form">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" class="form-control">
                    <pre class="error my-1 email_err"></pre>
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control">
                    <pre class="error my-1 password_err"></pre>
                    <input type="submit" value="Login" class="btn btn-primary btn-sm">
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('#login_form').submit(function(event){
            event.preventDefault();
            var formdata = $(this).serialize();
            $.ajax({
                url: "http://127.0.0.1:8000/api/login",
                method: "POST",
                data: formdata,
                success: function(result){
                    $('.error').text("");
                    if (result.status == false) {
                        $('#result').html(result.message+'<a class="close" style="cursor: pointer" data-dismiss="alert">&times;</a>');
                        $('#result').css('display','block');
                    }else if(result.status == true){
                        // console.log(result);
                        localStorage.setItem("user_token",result.token_type+" "+result.access_toke);
                        window.open('/profile','_self');
                    }else{
                        printErrorMessage(result);
                    }
                }
            })
        })
        function printErrorMessage(msg){
            $('.error').text("");
            $.each(msg, function(key, value){
                $('.'+key+"_err").text(value);
            });
        }
    })
</script>

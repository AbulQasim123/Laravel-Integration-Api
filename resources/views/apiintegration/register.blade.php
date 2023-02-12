@include('apiintegration.header')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h4 align="center" class="my-4">Laravel API Integration</h4>
            <div class="alert alert-success alert-dismissible" id="result" style="display: none;"></div>
            <div class="form">
                <h4 align="center">Register</h4>
                <form id="register_form">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-control">
                    <pre class="error my-1 name_err"></pre>
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" class="form-control">
                    <pre class="error my-1 email_err"></pre>
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control">
                    <pre class="error my-1 password_err"></pre>
                    <label for="conf_pass">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="conf_pass" class="form-control">
                    <pre class="error my-1 conf_pass_err"></pre>
                    <input type="submit" value="Register" class="btn btn-primary btn-sm">
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('#register_form').submit(function(event){
            event.preventDefault();
            var formdata = $(this).serialize();
            $.ajax({
                url: "http://127.0.0.1:8000/api/register",
                method: "POST",
                data: formdata,
                success: function(result){
                    if (result.msg) {
                        $('#register_form')[0].reset();
                        $('.error').text("");
                        $('#result').html(result.msg+'<a class="close" style="cursor: pointer" data-dismiss="alert">&times;</a>');
                        $('#result').css('display','block');
                    }else{
                        printErrorMessage(result);
                    }
                }
            })
        })
        function printErrorMessage(msg){
            $('.error').text("");
            $.each(msg, function(key, value){
                if (key == "password") {
                    if (value.length > 1) {
                        $('.password_err').text(value[0]);
                        $('.conf_pass_err').text(value[1]);
                    }else{
                        if (value[0].includes('Confirm Password')) {
                            $('.conf_pass_err').text(value);
                        }else{
                            $('.password_err').text(value);
                        }
                    }
                }else{
                    $('.'+key+"_err").text(value);
                }
            });
        }
    })
</script>

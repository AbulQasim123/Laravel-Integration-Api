@include('apiintegration.header')
<h5>Welcome Back, <span class="name"></span></h5>
<p><b>Email:- <span class="email"></span> &nbsp; <span class="verify"></span></b></p>
<body>
    <div class="alert alert-success alert-dismissible" id="result" style="display: none; width: 500px;"></div>
    <!-- <div id="result" class="text-success"></div> -->
    <div class="form">
        <form id="updateprofile">
            <input type="hidden" class="user_id" name="user_id" id="user_id">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" class="form-control" style="width: 500px;">
            <pre class="error my-1 name_err"></pre>
            <label for="email">Email</label>
            <input type="text" id="email" name="email" class="form-control" style="width: 500px;">
            <pre class="error my-1 email_err"></pre>
            <input type="submit" id="update" value="Update" class="my-2 btn btn-primary btn-sm">
        </form>
        <a href="/forget-password">Forget Password</a>
    </div>
</body>
<script>
    $(document).ready(function(){
        $.ajax({
            url: "http://127.0.0.1:8000/api/profile",
            type: "GET",
            headers: {"Authorization": localStorage.getItem('user_token')},
            success: function(result){
                console.log(result);
                if (result.status == true) {
                    $('.user_id').val(result.data.id);
                    $('.name').html(result.data.name);
                    $('.email').html(result.data.email);
                    $('#name').val(result.data.name);
                    $('#email').val(result.data.email);

                    if (result.data.is_verified == 0) {
                        $('.verify').html("<button class='verifyemail' data-id='"+result.data.email+"' id='verifyemail'>Verify</button>");
                    }else{
                        $('.verify').html("<span class='text-success'>Verified</span>");
                    }
                }else{
                    alert(result.msg);
                }
            }
        })
            // UpdateProfile
        $('#updateprofile').submit(function(event){
            event.preventDefault();
            var formdata = $(this).serialize();
            $.ajax({
                url: "http://127.0.0.1:8000/api/update-profile",
                type: "POST",
                data: formdata,
                headers: {"Authorization": localStorage.getItem('user_token')},
                success: function(result){
                    console.log(result);
                    if(result.status == true){
                        console.log(result);
                        $('#result').html(result.msg+'<a class="close" style="cursor: pointer" data-dismiss="alert">&times;</a>');
                        $('#result').css('display','block');
                        setTimeout(() => {
                            $('#result').html('');
                            $('#result').css('display','none');
                        }, 4000);
                        $('.name').html(result.data.name);
                        $('#name').val(result.data.name);
                        $('#email').val(result.data.email);
                        $('.email').html(result.data.email);
                        if (result.data.is_verified == 0) {
                            $('.verify').html("<button class='verifyemail' data-id='"+result.data.email+"' id='verifyemail'>Verify</button>");
                        }else{
                            $('.verify').html("<span class='text-success'>Verified</span>");
                        }
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

        // Vefifymail
        $(document).on('click','.verifyemail', function(){
            var email = $(this).attr('data-id');
            $.ajax({
                url: "http://127.0.0.1:8000/api/send-verify-mail/"+email,
                type: "GET",
                headers: {"Authorization": localStorage.getItem('user_token')},
                success: function(result){
                    $('#result').html(result.msg+'<a class="close" style="cursor: pointer" data-dismiss="alert">&times;</a>');
                    $('#result').css('display','block');
                    // console.log(result);
                    setTimeout(() => {
                            $('#result').html('');
                            $('#result').css('display','none');
                    }, 4000);
                }
            })
        })
    });
</script>
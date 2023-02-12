<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .error{
            color: red;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h4 align="center" class="my-4">Laravel API Integration</h4>
            <div class="alert alert-success alert-dismissible" id="success_result" style="display: none;"></div>
            <div class="alert alert-danger alert-dismissible" id="error_result" style="display: none;"></div>
            <div class="form">
                <h4 align="center">Password Reset</h4>
                <form id="sendmailforresetpassword">
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email" class="form-control" style="width: 500px;" required>
                    <input type="submit" id="reset" value="Submit" class="my-2 btn btn-primary btn-sm">
                </form>
            </div>
        </div>
    </div>
</div>

</body>
<script>
    $(document).ready(function(){
        $('#sendmailforresetpassword').submit(function(event){
            event.preventDefault();
            var formdata = $(this).serialize();
            $.ajax({
                url: "http://127.0.0.1:8000/api/forget-password",
                type: "POST",
                data: formdata,
                success: function(result){
                    // console.log(result);
                    if (result.status == true) {
                        $('#success_result').html(result.msg+'<a class="close" style="cursor: pointer" data-dismiss="alert">&times;</a>');
                        $('#success_result').css('display','block');
                        $('#email').val("");
                        setTimeout(() => {
                                $('#success_result').html('');
                                $('#success_result').css('display','none');
                        }, 4000);
                        
                    }else{
                        $('#error_result').html(result.msg+'<a class="close" style="cursor: pointer" data-dismiss="alert">&times;</a>');
                        $('#error_result').css('display','block');
                        setTimeout(() => {
                                $('#error_result').html('');
                                $('#error_result').css('display','none');
                        }, 4000);
                    }
                }
            });
        })
    })
</script>
</html>
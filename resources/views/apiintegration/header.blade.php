<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Api Integration</title>
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
    <div class="my-3 ml-3">
        <button type="button" id="logout" class="logout btn btn-danger btn-sm" >Logout</button>
        <button type="button" id="refreash_token" class="refreash_token btn btn-danger btn-sm" >Refresh Token</button>
    </div>
    <script type="text/javascript">
        var token = localStorage.getItem('user_token');
        if (window.location.pathname == '/login' || window.location.pathname == '/register') {
            if (token != null) {
                window.open('/profile','_self');
            }
            $('#logout').hide();
            $('#refreash_token').hide();
        }else{
            if (token == null) {
                window.open('/login','_self');
            }
        }

        // Logout
        $(document).ready(function(){
            $('#logout').click(function(){
                $.ajax({
                    url: "http://127.0.0.1:8000/api/logout",
                    type: "GET",
                    headers: {"Authorization": localStorage.getItem('user_token')},
                    success: function(result){
                        if(result.status == true){
                            localStorage.removeItem('user_token');
                            window.open('/login','_self');
                        }else{
                            alert(result.status);
                        }
                    }
                })
            })

            // Refresh Token
            $('#refreash_token').click(function(){
                $.ajax({
                    url: "http://127.0.0.1:8000/api/refresh-token",
                    type: "GET",
                    headers: {"Authorization": localStorage.getItem('user_token')},
                    success: function(result){
                        if (result.status == true) {
                            localStorage.setItem("user_token",result.token_type+" "+result.access_toke);
                            alert("User is Refreshed");
                        }else{
                            alert(result.msg);
                        }
                    }
                })
            })
        })
    </script>
</body>
</html>
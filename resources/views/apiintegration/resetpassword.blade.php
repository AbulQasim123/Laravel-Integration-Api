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
            <div class="alert alert-danger alert-dismissible" id="result" style="display: none;"></div>
            <div class="form">
                <h4 align="center">Password Reset</h4>
                @if($errors->has('password'))
                        <pre class="error my-1 email_err">{{ $errors->first('password') }}</pre>
                @endif 
                @if(Session::has('success'))
                        <pre class="text-success my-1">{{ Session::get('success') }}</pre>
                @endif
                <form id="" method="POST">
                    @csrf
                    <input type="hidden" name="user_email" value="{{ $user[0]['email'] }}">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                    <pre class="error my-1 password_err"></pre>
                    <input type="submit" value="Reset" class="btn btn-primary btn-sm">
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
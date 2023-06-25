<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name', 'Login') }}</title>
    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />

</head>
<body>

<div class="container">
    <div class="row mt-5">
        <div class="col-md-4 offset-md-4 offset-3">
            <h3 class="alert alert-primary text-center">Login</h3>
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="login">Login</label>
                    <input type="text" class="form-control" id="login" name="username" placeholder="Enter email">
                    {{--        <small id="username" class="form-text text-muted">Login error</small>--}}
                </div>
                <div class="form-group mb-3">
                    <label for="password">Password</label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                    {{--        <small id="username" class="form-text text-muted">Login error</small>--}}
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>

        </div>
    </div>
</div>


<script src="{{ asset('jquery/jquery-3.6.1.min.js') }}"></script>
<!-- Bootstrap JS -->
<script src="{{ asset('popper/popper.min.js') }}"></script>
<script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>

</body>
</html>

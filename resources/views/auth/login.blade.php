<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>GetCandy - Login</title>

    <link rel="shortcut icon" type="image/png" href="{{ url('candy-hub/images/favicon.png') }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,400i,700" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

    <!-- GetCandy -->
    <link href="{{ url('candy-hub/css/hub.css') }}" rel="stylesheet">

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script>
    window.Laravel = {
        "csrfToken": "{{ csrf_token() }}"
    };
    </script>
  </head>
  <body>
  <div class="container-fluid" id="app">
    <div class="row">
      <div class="col-xs-12 col-md-5">
        <div class="login-form">
          <img src="{{ url('candy-hub/images/logo/getcandy.png') }}" class="logo" alt="GetCandy">

          @if(\Session::has('unauth'))
            <div class="alert alert-danger">
              {{ \Session::get('unauth') }}
            </div>
          @endif
          @if($errors->any())
            <div class="alert alert-danger">
            <ul>
              @foreach($errors->all() as $error)
                <li>{{$error}}</li>
              @endforeach
            </ul>
            </div>
          @endif

          <form method="post">
            {{ csrf_field() }}

            <div class="form-group">
              <label class="sr-only">Email</label>
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                <input type="email" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}" autofocus>
              </div>
            </div>
            <div class="form-group">
              <label class="sr-only">Password</label>
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-lock" aria-hidden="true"></i></span>
                <input type="password" class="form-control" placeholder="Password" name="password">
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12 col-md-6">
                <div class="checkbox">
                  <input id="rememberMe" type="checkbox">
                  <label for="rememberMe"><span class="check"></span> <span class="faux-label">Remember Me</span></label>
                </div>
              </div>
              <div class="col-xs-12 col-md-6 text-right">
                <button type="submit" class="btn btn-primary">Login</button>
              </div>
            </div>
          </form>
          <hr>
          <div class="text-center">
            <a href="{{ route('password.request') }}" title="Forgotten your password?">Forgot your password?</a>
          </div>
        </div>
      </div>
      <div class="login-img"></div>
    </div>
  </div>
    <div class="main-purple-overlay"></div>
  </body>
</html>

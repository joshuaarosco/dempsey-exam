<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="apple-touch-icon" sizes="57x57" href="{{asset('favicon/apple-icon-57x57.png')}}">
  <link rel="apple-touch-icon" sizes="60x60" href="{{asset('favicon/apple-icon-60x60.png')}}">
  <link rel="apple-touch-icon" sizes="72x72" href="{{asset('favicon/apple-icon-72x72.png')}}">
  <link rel="apple-touch-icon" sizes="76x76" href="{{asset('favicon/apple-icon-76x76.png')}}">
  <link rel="apple-touch-icon" sizes="114x114" href="{{asset('favicon/apple-icon-114x114.png')}}">
  <link rel="apple-touch-icon" sizes="120x120" href="{{asset('favicon/apple-icon-120x120.png')}}">
  <link rel="apple-touch-icon" sizes="144x144" href="{{asset('favicon/apple-icon-144x144.png')}}">
  <link rel="apple-touch-icon" sizes="152x152" href="{{asset('favicon/apple-icon-152x152.png')}}">
  <link rel="apple-touch-icon" sizes="180x180" href="{{asset('favicon/apple-icon-180x180.png')}}">
  <link rel="icon" type="image/png" sizes="192x192"  href="{{asset('favicon/android-icon-192x192.png')}}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{asset('favicon/favicon-32x32.png')}}">
  <link rel="icon" type="image/png" sizes="96x96" href="{{asset('favicon/favicon-96x96.png')}}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{asset('favicon/favicon-16x16.png')}}">
  <link rel="manifest" href="{{asset('favicon/manifest.json')}}">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-TileImage" content="{{asset('favicon/ms-icon-144x144.png')}}">
  <meta name="theme-color" content="#ffffff">
  <title>{{env('APP_NAME','Localhost')}} - Log in </title>
  <link rel="stylesheet" href="{{asset('backoffice/assets/vendor_components/bootstrap/dist/css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{asset('backoffice/assets/vendor_components/bootstrap/dist/css/bootstrap-extend.css')}}">
  <link rel="stylesheet" href="{{asset('backoffice/css/master_style.css')}}">
  <link rel="stylesheet" href="{{asset('backoffice/css/skins/_all-skins.css')}}">	
</head>
<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <a href="{{route('backoffice.dashboard')}}"><b>{{env('APP_NAME','Localhost')}}</b> Admin</a>
    </div>
    
    @if(session()->has('notification-status'))
    <div class="alert alert-{{session()->get('notification-status') == 'success'?'success':'danger'}}">
      <strong>{{Str::title(session()->get('notification-status'))}}!</strong> {{session()->get('notification-msg')}}
    </div>
    @endif
    <div class="login-box-body">
      <p class="login-box-msg">Sign in to start your session</p>

      <form action="" method="post" class="form-element">
        {{csrf_field()}}
        <div class="form-group has-feedback">
          <input name="username" type="text" class="form-control" placeholder="Email or Username">
          <span class="fa fa-envelope form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
          <input name="password" type="password" class="form-control" placeholder="Password">
          <span class="fa fa-lock form-control-feedback"></span>
        </div>
        <div class="row">
          <div class="col-6">
            <div class="checkbox">
              <input name="remember_me" type="checkbox" id="basic_checkbox_1" >
              <label for="basic_checkbox_1">Remember Me</label>
            </div>
          </div>
          <!-- /.col -->
            <!-- <div class="col-6">
                <div class="fog-pwd">
                    <a href="javascript:void(0)"><i class="fa fa-locked"></i> Forgot pwd?</a><br>
                </div>
            </div> -->
         <!-- /.col -->
         <div class="col-12 text-center">
          <button type="submit" class="btn btn-info btn-block margin-top-10">SIGN IN</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

    <!-- <div class="social-auth-links text-center">
      <p>- OR -</p>
      <a href="#" class="btn btn-social-icon btn-circle btn-facebook"><i class="fa fa-facebook"></i></a>
      <a href="#" class="btn btn-social-icon btn-circle btn-google"><i class="fa fa-google-plus"></i></a>
    </div> -->
    <!-- /.social-auth-links -->

    <!-- <div class="margin-top-30 text-center">
    	<p>Don't have an account? <a href="register.html" class="text-info m-l-5">Sign Up</a></p>
    </div> -->

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
	<script src="{{asset('backoffice/assets/vendor_components/jquery/dist/jquery.min.js')}}"></script>
	<script src="{{asset('backoffice/assets/vendor_components/popper/dist/popper.min.js')}}"></script>
	<script src="{{asset('backoffice/assets/vendor_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>

</body>
</html>

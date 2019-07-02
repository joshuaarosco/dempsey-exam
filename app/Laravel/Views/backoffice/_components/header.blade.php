<header class="main-header">
  <a href="{{route('backoffice.dashboard')}}" class="logo">
   <b class="logo-mini">
   </b>
   <span class="logo-lg">
    <p>Administrator</p>
  </span>
</a>
<nav class="navbar navbar-static-top">
  <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
    <span class="sr-only">Toggle navigation</span>
  </a>
  <div class="navbar-custom-menu">
    <ul class="nav navbar-nav">
      <li class="search-box">
        <a class="nav-link hidden-sm-down" href="javascript:void(0)"><i class="mdi mdi-magnify"></i></a>
        <form class="app-search" style="display: none;">
          <input type="text" class="form-control" placeholder="Search &amp; enter"> <a class="srh-btn"><i class="ti-close"></i></a>
        </form>
      </li>			
      <li class="dropdown user user-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
          <img src="{{asset('backoffice/face0.jpg')}}" class="user-image rounded-circle" alt="User Image">
        </a>
        <ul class="dropdown-menu scale-up">
          <li class="user-header">
            <img src="{{asset('backoffice/face0.jpg')}}" class="float-left rounded-circle" alt="User Image">

            <p>
              {{Auth::user()->fname}} {{Auth::user()->lname}}
              <small class="mb-5">{{Str::limit(Auth::user()->email,$limit = 15)}}</small>
              <a href="#" class="btn btn-danger btn-sm btn-rounded">View Profile</a>
            </p>
          </li>
          <li class="user-body">
            <div class="row no-gutters">
              <div class="col-12 text-left">
                <a href="{{route('backoffice.profile.settings')}}"><i class="fa fa-wrench"></i>Account Settings</a>
              </div>
              <div role="separator" class="divider col-12"></div>
              <div class="col-12 text-left">
                <a href="{{route('backoffice.logout')}}"><i class="fa fa-power-off"></i> Logout</a>
              </div>				
            </div>
          </li>
        </ul>
      </li>
      <li class="user-menu">
        <a href="#" data-toggle="control-sidebar"><i class="fa fa-cog fa-spin"></i></a>
      </li>
    </ul>
  </div>
</nav>
</header>
<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li class="user-profile treeview">
                <a href="{{route('backoffice.dashboard')}}"><img alt="user" src="{{asset('backoffice/face0.jpg')}}"> <span>{{Auth::user()->fname}} {{Auth::user()->lname}}</span> <span class="pull-right-container"><i class="fa fa-angle-right pull-right"></i></span></a>
                <ul class="treeview-menu">
                    <!-- <li>
                        <a href="javascript:void()"><i class="fa fa-user mr-5"></i>My Profile</a>
                    </li>
                    <li>
                        <a href="javascript:void()"><i class="fa fa-money mr-5"></i>My Balance</a>
                    </li>
                    <li>
                        <a href="javascript:void()"><i class="fa fa-envelope-open mr-5"></i>Inbox</a>
                    </li> -->
                    <li>
                        <a href="{{route('backoffice.profile.settings')}}"><i class="fa fa-wrench mr-5"></i>Account Setting</a>
                    </li>
                    <li>
                        <a href="{{route('backoffice.logout')}}"><i class="fa fa-power-off mr-5"></i>Logout</a>
                    </li>
                </ul>
            </li>
            <li class="header nav-small-cap">Menu</li>
            <li class="{{ active_class(if_route(['backoffice.dashboard']), 'active') }}">
                <a href="{{route('backoffice.dashboard')}}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
            </li>
            <li class="header nav-small-cap">User Management</li>
            <li class="{{ active_class(if_route(['backoffice.employees.index']), 'active') }}">
                <a href="{{route('backoffice.employees.index')}}"><i class="fa fa-users"></i> <span>Employees</span></a>
            </li>
        </ul>
    </section>
</aside>
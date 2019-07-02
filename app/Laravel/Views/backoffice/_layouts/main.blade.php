<!DOCTYPE html>
<html lang="en">
<head>
  @include('backoffice._components.metas')
  @yield('page-metas')

  @include('backoffice._includes.styles')
  @yield('page-styles')

</head>

<body class="hold-transition skin-blue-light sidebar-mini">
    <div class="wrapper">

    @include('backoffice._components.header')
    @include('backoffice._components.nav')
    @yield('content')
    @include('backoffice._components.footer')
    @include('backoffice._components.right-sidebar')

    </div>
    @yield('page-modals')
    @include('backoffice._includes.scripts')
    @yield('page-scripts')

</body>
</html>

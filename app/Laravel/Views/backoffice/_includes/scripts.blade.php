<!-- jQuery 3 -->
<script src="{{asset('backoffice/assets/vendor_components/jquery/dist/jquery.js')}}"></script>

<!-- jQuery UI 1.11.4 -->
<script src="{{asset('backoffice/assets/vendor_components/jquery-ui/jquery-ui.js')}}"></script>

<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
	$.widget.bridge('uibutton', $.ui.button);
</script>

<!-- popper -->
<script src="{{asset('backoffice/assets/vendor_components/popper/dist/popper.min.js')}}"></script>

<!-- Bootstrap 4.0-->
<script src="{{asset('backoffice/assets/vendor_components/bootstrap/dist/js/bootstrap.js')}}"></script>		

@if(Route::currentRouteName() == 'backoffice.dashboard')
<!-- Morris.js charts -->
<script src="{{asset('backoffice/assets/vendor_components/raphael/raphael.min.js')}}"></script>
<script src="{{asset('backoffice/assets/vendor_components/morris.js/morris.min.js')}}"></script>	
@endif

<!-- ChartJS -->
<script src="{{asset('backoffice/assets/vendor_components/chart-js/chart.js')}}"></script>

<!-- Sparkline -->
<script src="{{asset('backoffice/assets/vendor_components/jquery-sparkline/dist/jquery.sparkline.js')}}"></script>

<!-- Slimscroll -->
<script src="{{asset('backoffice/assets/vendor_components/jquery-slimscroll/jquery.slimscroll.js')}}"></script>

<!-- FastClick -->
<script src="{{asset('backoffice/assets/vendor_components/fastclick/lib/fastclick.js')}}"></script>

<!-- peity -->
<script src="{{asset('backoffice/assets/vendor_components/jquery.peity/jquery.peity.js')}}"></script>

@if(Route::currentRouteName() == 'backoffice.dashboard')
<!-- Vector map JavaScript -->
<script src="{{asset('backoffice/assets/vendor_components/jvectormap/lib2/jquery-jvectormap-2.0.2.min.js')}}"></script>
<script src="{{asset('backoffice/assets/vendor_components/jvectormap/lib2/jquery-jvectormap-us-aea-en.js')}}"></script>
@endif

<!-- MinimalLite Admin App -->
<script src="{{asset('backoffice/js/template.js')}}"></script>

@if(Route::currentRouteName() == 'backoffice.dashboard')
<!-- MinimalLite Admin dashboard demo (This is only for demo purposes) -->
<script src="{{asset('backoffice/js/pages/dashboard.js')}}"></script>
@endif

<!-- MinimalLite Admin for demo purposes -->
<script src="{{asset('backoffice/js/demo.js')}}"></script>	
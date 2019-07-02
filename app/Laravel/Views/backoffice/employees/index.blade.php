@extends('backoffice._layouts.main')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>{{$page_title}}</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{route('backoffice.dashboard')}}"><i class="fa fa-dashboard"></i> Home</a>
            </li>
            <li class="breadcrumb-item active">{{$page_title}}</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">{{$page_title}} List</h4>
                        <br>
                        <small>{{$page_description}}</small>
                        <div class="box-tools">
                            <form action="" method="get">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <input class="form-control pull-right mr-10 input-search" name="search" value="{{Input::has('search')?Input::get('search'):null}}" placeholder="Search" type="text">
                                {{-- <div class="input-group-btn mr-5">
                                    <button class="btn btn-default" id="showtopright" type="submit"><i class="fa fa-search"></i></button>
                                </div> --}}
                                <div class="btn-group pull-right">
                                    <button type="button" class="btn btn-default btn-sm " data-toggle="modal" data-target=".create"><span class="text-info">Create</span></button>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                    <div class="box-body no-padding">
                        <table class="table table-sm table-hover table-responsive">
                            <tr>
                                <td width="1%"></td>
                                <td width="2%">#</td>
                                <td>IMAGE</td>
                                <td>NAME</td>
                                <td>BIRTHDAY</td>
                                <td>CONTACT INFORMATION</td>
                                <td>BALANCE</td>
                                <td width="1%"></td>
                            </tr>
                            <tbody id="fbody">
                                @forelse($employees as $index => $info)
                                <tr class="result">
                                    <td></td>
                                    <td>{{$index+1}}</td>
                                    <td><img src="{{asset($info->directory.'/'.$info->filename)}}" height="50" width="50"></td>
                                    <td>{{Str::limit($info->fname.' '.$info->lname,$limit = 30)}}</td>
                                    <td>{{Helper::date_format($info->birthdate,'F d, Y')}}</td>
                                    <td>{{$info->contact_number?:'---'}}<br>
                                        <a href="mailto:{{$info->email}}">{{$info->email}}</a>
                                    </td>
                                    <td>{{number_format($info->payment($info->id),2)}}</td>
                                    <td>
                                        <div class="dropdown">
                                            <div class="btn-group">
                                            <button class="btn btn-default btn-sm text-dark" type="button" onmouseover="reuse()" data-toggle="dropdown"><i class="fa fa-cog"></i> Action </button>
                                            <div class="dropdown-menu">
                                              <a class="dropdown-item action-add-payment" href="#" data-user-id="{{$info->id}}" data-toggle="modal" data-target=".add-pay"><i class="fa fa-money"></i> Add Pay</a>
                                              <div class="dropdown-divider"></div>
                                              <a class="dropdown-item action-view-history" href="#" data-user-id="{{$info->id}}" data-toggle="modal" data-target=".pay-history"><i class="fa fa-clock-o"></i> Pay History</a>
                                              <div class="dropdown-divider"></div>
                                              <a class="dropdown-item action-edit" href="#" 
                                              data-toggle="modal"
                                              data-target=".edit"
                                              data-id="{{$info->id}}"
                                              data-fname="{{$info->fname}}" 
                                              data-lname="{{$info->lname}}" 
                                              data-birthdate="{{$info->birthdate}}"
                                              data-email="{{$info->email}}"
                                              data-contact_number="{{$info->contact_number}}"
                                              data-img="{{asset($info->directory.'/'.$info->filename)}}">
                                              <i class="fa fa-pencil"></i> Edit</a>
                                              <div class="dropdown-divider"></div>
                                              <a class="dropdown-item action-delete" href="#" data-url="{{route('backoffice.'.$route_file.'.destroy',$info->id)}}" data-toggle="modal" data-target=".delete"><i class="fa fa-trash-o"></i> Delete</a>
                                            </div>
                                            </div>
                                      </div>
                                  </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        @if(Input::has('search'))
                                        <small>No search result for "{{Input::get('search')}}"</small>
                                        @else
                                        <small>No data yet. <a href="#" class="text-info" data-toggle="modal" data-target=".create">Create new</a></small>
                                        @endif
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- <div class="box-footer clearfix">
                        {{$employees->appends(['search' => Input::has('search')?Input::get('search'):null])->links()}}
                    </div> --}}
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('page-modals')
<div class="modal fade create" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-md">
        <form method="POST" action="{{route('backoffice.'.$route_file.'.store')}}" enctype="multipart/form-data" novalidate>
            {{csrf_field()}}
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" id="myLargeModalLabel">Create {{Str::singular($page_title)}}</p>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-6">
                                <small>First Name <span class="text-danger">*</span></small>
                                <div class="controls">
                                    <input type="text" name="fname" value="{{old('fname')}}" class="form-control input-sm" placeholder="First Name" required data-validation-required-message="This field is required"> 
                                </div>
                            </div>
                            <div class="col-6">
                                <small>Last Name <span class="text-danger">*</span></small>
                                <div class="controls">
                                    <input type="text" name="lname" value="{{old('lname')}}" class="form-control input-sm" placeholder="Last Name" required data-validation-required-message="This field is required"> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <small>Birthday <span class="text-danger">*</span></small>
                        <div class="controls">
                            <input type="date" name="birthdate" value="{{old('birthdate')}}" class="form-control input-sm" placeholder="Contact Number" required data-validation-required-message="This field is required"> 
                        </div>
                    </div>
                    <div class="form-group">
                        <small>Contact Number <span class="text-danger">*</span></small>
                        <div class="controls">
                            <input type="text" name="contact_number" value="{{old('contact_number')}}" class="form-control input-sm" placeholder="Contact Number" required data-validation-required-message="This field is required"> 
                        </div>
                    </div>
                    <div class="form-group {{$errors->first('email')?'error':NULL}}">
                        <small>Email <span class="text-danger">*</span></small>
                        <div class="controls">
                            <input type="email" name="email" value="{{old('email')}}" class="form-control input-sm" placeholder="Email" required data-validation-required-message="This field is required">
                            @if($errors->first('email'))
                            <div class="help-block"><ul role="alert"><li>{{$errors->first('email')}}</li></ul></div> 
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <small>Employee Image <span class="text-danger">*</span></small>
                        <div class="controls">
                            <input type="file" name="file" class="form-control" required="required" data-validation-required-message="Image is required">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect btn-sm pull-right" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info waves-effect btn-sm pull-right mr-5">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade edit" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{route('backoffice.'.$route_file.'.update')}}" enctype="multipart/form-data" novalidate>
            {{csrf_field()}}
            <input type="hidden" name="id" class="employee-id">
            
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" id="myLargeModalLabel">Edit {{Str::singular($page_title)}}</p>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6">
                                        <small>First Name <span class="text-danger">*</span></small>
                                        <div class="controls">
                                            <input type="text" name="fname" value="{{old('fname')}}" class="form-control input-sm employee-fname" placeholder="First Name" required data-validation-required-message="This field is required"> 
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <small>Last Name <span class="text-danger">*</span></small>
                                        <div class="controls">
                                            <input type="text" name="lname" value="{{old('lname')}}" class="form-control input-sm employee-lname" placeholder="Last Name" required data-validation-required-message="This field is required"> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <small>Birthday <span class="text-danger">*</span></small>
                                <div class="controls">
                                    <input type="date" name="birthdate" value="{{old('birthdate')}}" class="form-control input-sm employee-birthdate" placeholder="Contact Number" required data-validation-required-message="This field is required"> 
                                </div>
                            </div>
                            <div class="form-group">
                                <small>Contact Number <span class="text-danger">*</span></small>
                                <div class="controls">
                                    <input type="text" name="contact_number" value="{{old('contact_number')}}" class="form-control input-sm employee-contact_number" placeholder="Contact Number" required data-validation-required-message="This field is required"> 
                                </div>
                            </div>
                            <div class="form-group {{$errors->first('email')?'error':NULL}}">
                                <small>Email <span class="text-danger">*</span></small>
                                <div class="controls">
                                    <input type="email" name="email" value="{{old('email')}}" class="form-control input-sm employee-email" placeholder="Email" required data-validation-required-message="This field is required">
                                    @if($errors->first('email'))
                                    <div class="help-block"><ul role="alert"><li>{{$errors->first('email')}}</li></ul></div> 
                                    @endif
                                </div>
                            </div>                         
                            <div class="form-group">
                                <small>Employee Image <span class="text-danger">*</span></small>
                                <div class="controls">
                                    <input type="file" name="file" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <small>Current Image <span class="text-danger">*</span></small>
                                <img src="" class="img-thumbnail img-responsive employee-img">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect btn-sm pull-right" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info waves-effect btn-sm pull-right mr-5">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade add-pay" role="dialog">
  <div class="modal-dialog">
    <form action="{{route('backoffice.employees.add_pay')}}" method="post">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="hidden" name="user_id" value="" class="payment-user-id">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Payment</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <small>Amount <span class="text-danger">*</span></small>
            <div class="controls">
                <input type="number" class="form-control input-sm" name="amount" min="0" max="100000" required>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success btn-sm pull-right">Pay</button>
        <button type="button" class="btn btn-white btn-sm pull-right mr-5" data-dismiss="modal">Cancel</button>
      </div>
    </div>
    </form>
  </div>
</div>

<div class="modal fade pay-history" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body" style="background-color: #f5f5f5; padding: 30px 20px;">
                <ul class="timeline">
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="modal fade delete" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
        <div class="text-center">
            <div class="text-danger"><span class="fa fa-times-circle" style="font-size: 70px; margin-bottom: 20px;"></span></div>
            <h3>Danger!</h3>
            <p>You are about to delete a record,<br>this action can no longer be undone, are you sure you want to proceed?</p>
        </div>
      </div>
      <div class="modal-footer">
        <a href="" class="btn btn-danger btn-sm pull-right" id="btn-confirm-delete">Delete</a>
        <button type="button" class="btn btn-white btn-sm pull-right mr-5" data-dismiss="modal">Cancel</button>
      </div>
    </div>

  </div>
</div>

@if(session()->has('notification-status'))
<div id="alerttopright" class="myadmin-alert alert-{{session()->get('notification-status') == 'success'?'success':'danger'}} myadmin-alert-top-right" style="border-radius: 5px;"> 
    <a href="#" class="closed">&times;</a>
    <h4>{{Str::title(session()->get('notification-status'))}}!</h4> {{session()->get('notification-msg')}}
</div>
@endif
@endsection

@section('page-styles')
<!-- toast CSS -->
<link href="{{asset('backoffice/assets/vendor_components/jquery-toast-plugin-master/src/jquery.toast.css')}}" rel="stylesheet">
<style type="text/css">
    .textarea-input{
        font-size: 12px!important;
        line-height: 1.5!important;
    }
</style>
@endsection

@section('page-scripts')
<!-- Form validator JavaScript -->
    <script src="{{asset('backoffice/js/pages/validation.js')}}"></script>
    <script src="{{asset('backoffice/assets/vendor_components/jquery-toast-plugin-master/src/jquery.toast.js')}}"></script>
    <script src="{{asset('backoffice/js/pages/toastr.js')}}"></script>
    <script src="{{asset('backoffice/js/pages/jquery.dateFormat.min.js')}}"></script>

    <script>
        ! function(window, document, $) {
            "use strict";
            $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
        }(window, document, jQuery);

        $(".myadmin-alert .closed").click(function(event) {
            $(this).parents(".myadmin-alert").fadeToggle(350);
            return false;
        });

        $("#alerttopright").fadeToggle(350);

        function reuse(){
            $(".action-add-payment").on("click",function(){
                var btn = $(this);
                $(".payment-user-id").val(btn.data('user-id'));
            });

            $(".action-delete").on("click",function(){
                var btn = $(this);
                $("#btn-confirm-delete").attr({"href" : btn.data('url')});
            });

            $(".action-edit").on("click",function(){
                var btn = $(this);
                $(".employee-id").val(btn.data('id'));
                $(".employee-fname").val(btn.data('fname'));
                $(".employee-lname").val(btn.data('lname'));
                $(".employee-birthdate").val(btn.data('birthdate'));
                $(".employee-email").val(btn.data('email'));
                $(".employee-contact_number").val(btn.data('contact_number'));
                $(".employee-img").attr('src',btn.data('img'));
            });

            $(".action-view-history").on("click",function(){
                var user_id = $(this).data('user-id');

                $.ajax({ 
                    type: "POST",
                    url: "{!!route('backoffice.employees.pay_history')!!}",
                    data: { user_id : user_id, _token : "{!!csrf_token()!!}" },
                    dataType: "json",
                    async: true,
                    success: function(data){
                        $(".timeline").empty();
                        $(".timeline").append('<li class="time-label"><span class="bg-gray">Payment History</span></li>');

                            if(data.count == 0){
                                $(".timeline").append('<li><i class="fa fa-info bg-info" style="color: #fff;"></i><div class="timeline-item"><div class="timeline-body"><div class="mt-10 ml-10 mb-10"><h4>No payment history yet.</h4></div></div></div></li>');
                            }else{
                                jQuery.each(data.history, function( i, val ) {

                                    var amount = val.amount;
                                    var date = jQuery.format.date(val.created_at, 'MMM, d yyyy hh:mm a');
                                    
                                    $(".timeline").append('<li><i title="'+status+'" class="fa fa-money bg-success" style="color: #fff;"></i><div class="timeline-item"><h3 class="timeline-header no-border ml-10">Payment</h3><div class="timeline-body"><div class="mt-10 ml-10 mb-10"><h4>Amount : <span class="text-success">'+amount+'<span></h4></div><div class="mt-20 ml-10 mb-10"><i class="fa fa-clock-o"></i> '+date+'</div></div></div></li>');
                                });
                            }

                        $(".timeline").append('<li><i class="fa fa-flag-checkered bg-gray"></i></li>');
                    },
                    error: function(jqXHR,textStatus,thrownError){  
                        console.log(thrownError);
                    }
                });
            });
        }

        $(".input-search").on("keyup change", function(){
            var txt = $(this).val();

            $.ajax({
                type: "POST",
                url: "{!!route('backoffice.employees.search')!!}",
                data: { search : txt, _token : "{!!csrf_token()!!}" },
                dataType: "json",
                async: true,
                success: function(data){
                    $('#fbody').empty();
                    $('#fbody').append(data.results);
                    reuse();
                },
                error: function(jqXHR,textStatus,thrownError){  
                    console.log(thrownError);
                }
            });
        });

        //**
        //comment the upper search functionality and uncomment the below script for faster and realtime search result
        //**

        // $(".input-search").on("keyup change",function () {
        //     var data = this.value.split(" ");
        //     var jo = $("#fbody").find("tr");
        //     if (this.value == "") {
        //         jo.show();
        //         return;
        //     }
        //     jo.hide();
        //     jo.filter(function (i, v) {
        //         var $t = $(this);
        //         for (var d = 0; d < data.length; ++d) {
        //             if ($t.is(":contains('" + data[d] + "')")) {
        //                 return true;
        //             }
        //         }
        //         return false;
        //     })
        //     .show();
        // }).focus(function () {
        //     this.value = "";
        //     $(this).css({
        //         "color": "black"
        //     });
        //     $(this).unbind('focus');
        // }).css({
        //     "color": "#C0C0C0"
        // });
    </script>
@endsection
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
            <div class="col-7">
                <div class="box">
                    <form action="" method="POST" novalidate>
                        {{csrf_field()}}
                        <div class="box-header with-border">
                            <h4 class="box-title">{{$page_title}} Form</h4>
                        </div>
                        <div class="box-body no-padding">
                            <div class="row">
                                <div class="col-12 p-40" style="padding-top: 10px!important; padding-bottom: 10px!important;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <small>First Name <span class="text-danger">*</span></small>
                                                <div class="controls">
                                                    <input type="text" name="fname" value="{{old('fname',Auth::user()->fname)}}" class="form-control input-sm" title="{{$errors->first('fname')}}" data-toggle="tooltip" style="{{$errors->first('fname')? 'border: 1px solid red;' : NULL}}" required data-validation-required-message="This field is required">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <small>Last Name <span class="text-danger">*</span></small>
                                                <div class="controls">
                                                    <input type="text" name="lname" value="{{old('lname',Auth::user()->lname)}}" class="form-control input-sm" title="{{$errors->first('lname')}}" data-toggle="tooltip" style="{{$errors->first('lname')? 'border: 1px solid red;' : NULL}}" required data-validation-required-message="This field is required">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <small>Username <span class="text-danger">*</span></small>
                                        <div class="controls">
                                            <input type="text" name="username" value="{{old('username',Auth::user()->username)}}" class="form-control input-sm" title="{{$errors->first('username')}}" data-toggle="tooltip" style="{{$errors->first('username')? 'border: 1px solid red;' : NULL}}" required data-validation-required-message="This field is required">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <small>Email <span class="text-danger">*</span></small>
                                        <div class="controls">
                                            <input type="email" name="email" value="{{old('email',Auth::user()->email)}}" class="form-control input-sm" title="{{$errors->first('email')}}" data-toggle="tooltip" style="{{$errors->first('email')? 'border: 1px solid red;' : NULL}}" required data-validation-required-message="This field is required">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <small>Contact # <span class="text-danger">*</span></small>
                                        <div class="controls">
                                            <input type="text" name="contact_number" value="{{old('contact_number',Auth::user()->contact_number)}}" class="form-control input-sm" title="{{$errors->first('contact_number')}}" data-toggle="tooltip" style="{{$errors->first('contact_number')? 'border: 1px solid red;' : NULL}}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <small>Password <span class="text-danger">*</span></small>
                                        <div class="controls">
                                            <input type="password" name="password" value="{{old('password')}}" class="form-control input-sm" title="{{$errors->first('password')}}" data-toggle="tooltip" style="{{$errors->first('password')? 'border: 1px solid red;' : NULL}}" required data-validation-required-message="This field is required">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer clearfix">
                            <button type="submit" class="btn btn-info btn-sm pull-right">Save</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-5">
                <div class="box">
                    <form action="{{route('backoffice.profile.update_password')}}" method="POST" novalidate>
                        {{csrf_field()}}
                        <div class="box-header with-border">
                            <h4 class="box-title">Password Setting Form</h4>
                        </div>
                        <div class="box-body no-padding">
                            <div class="row">
                                <div class="col-12 p-40" style="padding-top: 10px!important; padding-bottom: 10px!important;">
                                    <div class="form-group">
                                        <small>Current Password <span class="text-danger">*</span></small>
                                        <div class="controls">
                                            <input type="password" name="current_password" value="{{old('current_password')}}" title="{{$errors->first('current_password')}}" data-toggle="tooltip" style="{{$errors->first('current_password')? 'border: 1px solid red;' : NULL}}" class="form-control input-sm" required data-validation-required-message="This field is required">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <small>New Password <span class="text-danger">*</span></small>
                                        <div class="controls">
                                            <input type="password" name="new_password" value="{{old('new_password')}}" title="{{$errors->first('new_password')}}" data-toggle="tooltip" style="{{$errors->first('new_password')? 'border: 1px solid red;' : NULL}}" class="form-control input-sm" required data-validation-required-message="This field is required">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <small>Confirm Password <span class="text-danger">*</span></small>
                                        <div class="controls">
                                            <input type="password" name="new_password_confirmation" value="{{old('new_password_confirmation')}}" title="{{$errors->first('new_password_confirmation')}}" data-toggle="tooltip" style="{{$errors->first('new_password_confirmation')? 'border: 1px solid red;' : NULL}}" class="form-control input-sm" required data-validation-required-message="This field is required">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer clearfix">
                            <button type="submit" class="btn btn-info btn-sm pull-right">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('page-modals')

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
@endsection

@section('page-scripts')
<!-- Form validator JavaScript -->
    <script src="{{asset('backoffice/js/pages/validation.js')}}"></script>
    <script src="{{asset('backoffice/assets/vendor_components/jquery-toast-plugin-master/src/jquery.toast.js')}}"></script>
    <script src="{{asset('backoffice/js/pages/toastr.js')}}"></script>

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

        $(".action-delete").on("click",function(){
            var btn = $(this);
            $("#btn-confirm-delete").attr({"href" : btn.data('url')});
        });

        $(".action-edit").on("click",function(){
            var btn = $(this);
            $(".faq-question").val(btn.data('question'));
            $(".faq-answer").val(btn.data('answer'));
            $(".faq-id").val(btn.data('id'));
        });
    </script>
@endsection
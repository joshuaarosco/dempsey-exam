@extends('frontend._layouts.errors')

@section('page-content')
<section class="error-404-area text-center" style="padding: 250px; background-color: #f5f5f5;">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="search-form-wrapper ptb-110">
                    <h1 style="color: #000000;">404</h1>
                    <h2 style="color: #000000;">PAGE NOT FOUND</h2>
                    <div class="error-message">
                        <p style="color: #000000;">Sorry but the page you are looking for does not exist, have been removed, name changed or is temporarily unavailable.</p>
                    </div>
                    <div class="search-form" style="margin-top: 20px;">
                        <a href="{{route('frontend.index')}}" class="btn btn-style1"></i><i class="fa fa-arrow-left "></i> Back to home page</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
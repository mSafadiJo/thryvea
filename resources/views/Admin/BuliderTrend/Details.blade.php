@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->

    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Builder Trend Form</h4>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="https://unpkg.com/balloon-css/balloon.min.css">
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <!-- Form -->

                <script type="text/javascript" src="https://buildertrend.net/leads/contactforms/js/btClientContactForm.js"></script>

                <iframe src="https://buildertrend.net/leads/contactforms/ContactFormFrame.aspx?builderID={{ $BuilderTrendTabel->builder_id }}" scrolling="no" id="btIframe" style="background:transparent;border:0px;margin:0 auto;width:100%;"></iframe>

                <!-- /Form -->
            </div>
        </div>
    </div>
    <!-- End Of Page 1-->

@endsection
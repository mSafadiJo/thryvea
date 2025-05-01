@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">List Of ZipCodes</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <h4>Campaign Name: {{ $campaign->campaign_name }}</h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <h5>List OF ZipCodes</h5>
                                <p class="Lead">
                                    @foreach( $zip_codes as $val )
                                        {{ $val }},&nbsp;&nbsp;
                                    @endforeach
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Of Page 1-->
@endsection

@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Create Campaign</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <h6>Create Campaign</h6>
        </div>
    </div>
    <div class="row">
        @foreach( $types as $key => $type )
            <div class="col-md-4">
                <div class="card-box hover-box-shadow">
                    <div class="text-center logo">
                        {{--                        <img src="{{ URL::asset('images/campaigntype/'.$type->campaign_types_logo) }}"   style="width: 10%;">--}}
                        <i class="fa {{$type->campaign_types_logo}}" aria-hidden="true"></i>
                    </div>
                    <h5 class="text-center text-capitalize">{{ $type->campaign_types_name }}</h5>
                    <p class="m-0 text-center">{{ $type->campaign_types_description }}</p>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-outline-secondary col-md-12" onclick='window.location.href= "addAdminCampaign/{{ $type->campaign_types_id }}?buyer_id={{ $buyer_id }}"'
                            >Create Campaign</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <!-- End Of Page 1-->
@endsection

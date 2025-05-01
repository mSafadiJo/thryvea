@extends('layouts.NavBuyerHome')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <h6 class="text-w">Create Campaign</h6>
        </div>
    </div>
    <div class="add-campaign createCamp">
    <div class="row">
        @foreach( $types as $key => $type )
            <div class="col-md-4">
                <div class="card-box hover-box-shadow createContain">
                    <div class="text-center logo">
{{--                        <img src="{{ URL::asset('images/campaigntype/'.$type->campaign_types_logo) }}"   style="width: 10%;">--}}
                        <i class="fa {{$type->campaign_types_logo}}" aria-hidden="true"></i>
                    </div>
                    <h5 class="text-center text-capitalize">{{ $type->campaign_types_name }}</h5>
                    <p class="m-0 text-center">{{ $type->campaign_types_description }}</p>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-outline-secondary col-md-12 Button-Add creatCampaignButton" onclick='window.location.href= "addCampaign/{{ $type->campaign_types_id }}"'
                                >Create Campaign</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    </div>
    <!-- End Of Page 1-->
@endsection

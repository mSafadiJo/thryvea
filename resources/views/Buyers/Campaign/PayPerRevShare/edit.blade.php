@extends('layouts.NavBuyerHome')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Edit {{ $campaign->campaign_name }} Campaign</h4>
            </div>
        </div>
    </div>
    <style>
        ul#myTab li.nav-item {
            width: 25%;
            text-align: center;
        }
    </style>

    @php
        $permission_users_buyer = array();
        if( !empty($campaign->permission_users) ){
            $permission_users_buyer = json_decode($campaign->permission_users, true);
        }
    @endphp

    <div class="row">
        <div class="col-lg-12">
            <div class="card-box Campbox">
                <div class="row">
                    <div class="col-lg-12">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item col-md-3">
                                <a class="nav-link active show" id="home-tab" data-toggle="tab" href="#details" role="tab" aria-controls="home"
                                   aria-selected="true">Campaign Details</a>
                            </li>
                            <li class="nav-item col-md-3">
                                <a class="nav-link" id="address-tab" data-toggle="tab" href="#address" role="tab" aria-controls="address"
                                   aria-selected="false">Campaign  Target</a>
                            </li>
                            <li class="nav-item col-md-3">
                                <a class="nav-link" id="deliverymethod-tab" data-toggle="tab" href="#deliverymethod" role="tab" aria-controls="deliverymethod"
                                   aria-selected="false">Delivery Method</a>
                            </li>
                            <li class="nav-item col-md-3">
                                <a class="nav-link" id="paid-tab" data-toggle="tab" href="#paid" role="tab" aria-controls="paid"
                                   aria-selected="false">Campaign Bid</a>
                            </li>
                        </ul>
                        <form  action="{{ route('CampaignUpdate') }}" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="loader" style="display: none;"></div>
                            <div class="tab-content unloader" id="myTabContent">
                                <div class="tab-pane fade active show" id="details" role="tabpanel" aria-labelledby="home-tab">
                                    <div class="row m-t-20">
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="Campaign_name">Campaign Name<span class="requiredFields">*</span></label>
                                                        <input type="text" class="form-control" id="Campaign_name" name="Campaign_name" placeholder="" required=""
                                                               value="{{ $campaign->campaign_name }}">
                                                        <input type="hidden" class="form-control" id="Campaign_id" name="Campaign_id" placeholder="" required=""
                                                               value="{{ $campaign->campaign_id }}">
                                                    </div>
                                                </div>

                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="service_id">Service Name<span class="requiredFields">*</span></label>
                                                        <select class="select2 form-control" name="service_id" id="service_idCampainAddAdmnin" required="">
                                                            <optgroup label="Service Name">
                                                                @if( !empty( $services ) )
                                                                    @foreach( $services as $service )
                                                                        <option value="{{ $service->service_campaign_id }}"
                                                                                @if( $service->service_campaign_id ==  $campaign->service_campaign_id ) selected @endif>{{ $service->service_campaign_name }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if(!in_array('1', $permission_users_buyer))
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <button type="submit" class="btn btn-primary stepy-finish pull-right UpdateCamp">Update</button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="tab-pane fade" id="address" role="tabpanel" aria-labelledby="address-tab">
                                    <div class="row m-t-20">
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label for="state">What campaign take state<span class="requiredFields" ></span></label>
                                                        <select class="select2 form-control select2-multiple" name="statefilter[]" disabled id="stateCamp" multiple="multiple" data-placeholder="Choose ...">
                                                            <optgroup label="States">
                                                                @if( !empty($address['states']) )
                                                                    @foreach($address['states'] as $state)
                                                                        @if( empty($address['states_Filter_campaign']) )
                                                                            <option value="{{ $state->state_id }}">{{ $state->state_code }}</option>
                                                                        @else
                                                                            @if( in_array($state->state_id, $address['states_Filter_campaign']) )
                                                                                <option value="{{ $state->state_id }}" selected>{{ $state->state_code }}</option>
                                                                            @else
                                                                                <option value="{{ $state->state_id }}">{{ $state->state_code }}</option>
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12">
                                                    <h6>Choose a place among my working areas</h6>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="state">State</label>
                                                        <select class="select2 form-control select2-multiple stateCam" name="state[]" id="stateCamp" multiple="multiple" data-placeholder="Choose ...">
                                                            <optgroup label="States">
                                                                @if( !empty($address['states']) )
                                                                    @foreach($address['states'] as $state)
                                                                        @if( empty($address['states_in_campaign']) )
                                                                            <option value="{{ $state->state_id }}">{{ $state->state_code }}</option>
                                                                        @else
                                                                            @if( in_array($state->state_id, $address['states_in_campaign']) )
                                                                                <option value="{{ $state->state_id }}" selected>{{ $state->state_code }}</option>
                                                                            @else
                                                                                <option value="{{ $state->state_id }}">{{ $state->state_code }}</option>
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </optgroup>
                                                        </select>
                                                        <input type="button" class="select_all select_all_select" data-classes="stateCam" value="Select All">
                                                        <input type="button" class="clear_all_state clear_all_select" data-classes="stateCam" value="Clear All">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="county">County</label>
                                                        <select class="form-control countyCam" name="county[]" id="county" multiple="multiple">
                                                            <optgroup label="Counties">
                                                                @foreach($address['counties_in_campaign'] as $counties)
                                                                    <option value="{{$counties->county_id}}" selected>
                                                                        {{$counties->county_name}}
                                                                    </option>
                                                                @endforeach
                                                            </optgroup>
                                                        </select>
                                                        <input type="button" class="clear_all_state clear_all_select" data-classes="countyCam" value="Clear All">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group city_filter">
                                                        <label for="City_Name">City Name</label>
                                                        <select class="form-control cityCam city_filter" multiple name="city[]" id="City_Name">
                                                            <optgroup label="">
                                                                @if(!empty($address['cities_in_campaign']))
                                                                    @foreach($address['cities_in_campaign'] as $cities)
                                                                        <option value="{{$cities->city_id}}" selected>
                                                                            {{$cities->city_name}}
                                                                        </option>
                                                                    @endforeach
                                                                @endif
                                                            </optgroup>
                                                        </select>
                                                        <input type="button" class="clear_all_state clear_all_select" data-classes="cityCam" value="Clear All">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group zip_filter">
                                                        <label for="City_Name">Zip Codes</label>
                                                        <select class="form-control zip_filter" multiple name="zipcode[]" id="Zip_Name">
                                                            <optgroup label="">

                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="append_zip_codes" style="display: block;">Append Zip-Codes</label>
                                                                <label class="form-group switch">
                                                                    <input type="checkbox" checked name="append_zip_codes" id="append_zip_codes" value="1">
                                                                    <span class="slider round"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="zipcode">Zip-Code</label>
                                                        <input type="file" class="form-control" name="listOfzipcode" id="listOfzipcode" accept=".xls,.xlsx,.csv">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="distance_area">Distance Area</label>
                                                        <input type="number" class="form-control" id="distance_area" name="distance_area" value="{{ $campaign->campaign_distance_area }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{--List Of Zipcodes & Distance Area Zipcodes--}}
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <h5>List OF ZipCodes</h5>
                                                <p class="Lead">
                                                    @foreach( $address['zip_codes_array'] as $val )
                                                        {{ $val }},&nbsp;&nbsp;
                                                    @endforeach
                                                </p>
                                            </div>
                                        </div>
                                        {{--                                        <div class="col-lg-12">--}}
                                        {{--                                            <div class="form-group">--}}
                                        {{--                                                <h5>List OF Distance ZipCodes</h5>--}}
                                        {{--                                                <p class="Lead">--}}
                                        {{--                                                    @foreach( $address['zip_codes_distance_array'] as $val )--}}
                                        {{--                                                        {{ $val }},&nbsp;&nbsp;--}}
                                        {{--                                                    @endforeach--}}
                                        {{--                                                </p>--}}
                                        {{--                                            </div>--}}
                                        {{--                                        </div>--}}

                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <h6>Excluded Areas</h6>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="county_expect">County</label>
                                                        <select class="form-control expectCounty" name="county_expect[]" id="county_expect" multiple="multiple">
                                                            <optgroup label="Counties">
                                                                @foreach($address['counties_not_in_campaign'] as $counties)
                                                                    <option value="{{$counties->county_id}}" selected>
                                                                        {{$counties->county_name}}
                                                                    </option>
                                                                @endforeach
                                                            </optgroup>
                                                        </select>
                                                        <input type="button" class="clear_all_state clear_all_select" data-classes="expectCounty" value="Clear All">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group city_filter_expect">
                                                        <label for="City_Name">City Name</label>
                                                        <select class="form-control expectCity city_filter" multiple name="city_expect[]" id="City_Name_expect">
                                                            <optgroup label="">
                                                                @if(!empty($address['cities_not_in_campaign']))
                                                                    @foreach($address['cities_not_in_campaign'] as $cities)
                                                                        <option value="{{$cities->city_id}}" selected>
                                                                            {{$cities->city_name}}
                                                                        </option>
                                                                    @endforeach
                                                                @endif
                                                            </optgroup>
                                                        </select>
                                                        <input type="button" class="clear_all_state clear_all_select" data-classes="expectCity" value="Clear All">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group zip_filter_expect">
                                                        <label for="City_Name">Zip Codes</label>
                                                        <select class="select2 form-control select2-multiple zip_filter_expect" multiple name="zipcode_expect[]" id="Zip_Name_expect">
                                                            <optgroup label="">

                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-sm-6 ">
                                                    <div class="form-group">
                                                        <label for="zipcode">Zip-Code</label>
                                                        <input type="file" class="form-control" name="listOfzipcode_expect" id="listOfzipcode_expect" accept=".xls,.xlsx,.csv">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <h5>List OF ZipCodes</h5>
                                                        <p class="Lead">
                                                            @foreach( $address['zipcods_not_in_campaign'] as $val )
                                                                {{ $val }},&nbsp;&nbsp;
                                                            @endforeach
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="Append_All_Zip_Codes" style="display: block;">Append Excluded Zip-Codes</label>
                                                        <label class="form-group switch">
                                                            <input type="checkbox" checked name="Append_All_Excluded_Zip_Codes" id="" value="1">
                                                            <span class="slider round" ></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <button type="button" class="btn btn-primary form-control btn-block" id="exportCampaignTarget"
                                                                    onclick="return document.getElementById('exportCampaignTargetSubmit').click();">Export Campaign Target</button>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <button type="button" class="btn btn-primary form-control btn-block" id="exportCampZipcodes"
                                                                    onclick="return document.getElementById('exportCampZipcodesSubmit').click();">Export Campaign Zipcodes</button>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <button type="button" class="btn btn-primary form-control btn-block" id="exportCampExpectZipcodes"
                                                                    onclick="return document.getElementById('exportCampExpectZipcodesSubmit').click();">Export Campaign Expect Zipcodes</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    @if(!in_array('1', $permission_users_buyer))
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <button type="submit" class="btn btn-primary stepy-finish pull-right UpdateCamp">Update</button>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                {{--delivery Methods--}}
                                @include('include.Campaigns.edit_delivery_methods')
                                {{--End delivery Methods--}}

                                <div class="tab-pane fade" id="paid" role="tabpanel" aria-labelledby="paid-tab">
                                    <div class="row m-t-20">
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="propertytype">Property Type<span class="requiredFields">*</span></label>
                                                        <select id="propertytype" name="propertytype[]" class="select2 form-control select2-multiple" required="" multiple="multiple" data-placeholder="Choose ...">
                                                            <optgroup label="Property Type">
                                                                <option value="1" @if( in_array("1", json_decode($campaign->property_type,true)) ) selected @endif> Owned </option>
                                                                <option value="2" @if( in_array("2", json_decode($campaign->property_type,true)) ) selected @endif> Rented </option>
                                                                <option value="3" @if( in_array("3", json_decode($campaign->property_type,true)) ) selected @endif> Business </option>
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="Installings">Type Of Service<span class="requiredFields">*</span></label>
                                                        <select id="Installings" name="Installings[]" class="select2 form-control select2-multiple" required="" multiple="multiple" data-placeholder="Choose ...">
                                                            <optgroup label="Type Of Service">
                                                                <option value="1" @if( in_array("1", json_decode($campaign->installing,true)) ) selected @endif> Install </option>
                                                                <option value="2" @if( in_array("2", json_decode($campaign->installing,true)) ) selected @endif> Replace </option>
                                                                <option value="3" @if( in_array("3", json_decode($campaign->installing,true)) ) selected @endif> Repair </option>
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="customPaid">Custom Bid<span class="requiredFields">*</span></label>
                                                        <select id="customPaid" name="customPaid[]" class="select2 form-control select2-multiple" required="" multiple="multiple" data-placeholder="Choose ...">
                                                            <optgroup label="Custom Bid">
                                                                {{--<option value="1" @if( in_array("1", json_decode($campaign->custom_paid_campaign_id,true))) selected @endif >Exclusive</option>--}}
                                                                <option value="2" @if( in_array("2", json_decode($campaign->custom_paid_campaign_id,true))) selected @endif >Shared</option>
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="homeowned">Owner<span class="requiredFields">*</span></label>
                                                        <select id="homeowned" name="homeowned[]" class="select2 form-control select2-multiple" required="" multiple="multiple" data-placeholder="Choose ...">
                                                            <optgroup label="Owner">
                                                                <option value="1" @if( in_array("1", json_decode($campaign->home_owned,true)) ) selected @endif>Yes</option>
                                                                <option value="0" @if( in_array("0", json_decode($campaign->home_owned,true)) ) selected @endif>No</option>
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            {{--questions--}}
                                            @include('include.Campaigns.edit_questions')
                                            {{--End questions--}}
                                        </div>
                                    </div>
                                    @if(!in_array('1', $permission_users_buyer))
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <button type="submit" class="btn btn-primary stepy-finish pull-right UpdateCamp">Update</button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </form>

                        <form  action="{{ route('Admin.Campaign.exportCampZipcodes') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                            <input type="hidden" class="form-control" name="id" required="" value="{{ $campaign->campaign_id }}">
                            <button type="submit" id="exportCampZipcodesSubmit">submit</button>
                        </form>
                        <form  action="{{ route('Admin.Campaign.exportCampaignTarget') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                            <input type="hidden" class="form-control" name="id" required="" value="{{ $campaign->campaign_id }}">
                            <button type="submit" id="exportCampaignTargetSubmit">submit</button>
                        </form>
                        <form  action="{{ route('Admin.Campaign.exportCampExpectZipcodes') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                            <input type="hidden" class="form-control" name="id" required="" value="{{ $campaign->campaign_id }}">
                            <button type="submit" id="exportCampExpectZipcodesSubmit">submit</button>
                        </form>
                        <form action="{{ route("Admin.Campaign.deleteAllZipcode2") }}" method="post" style="display: none;">
                            {{ csrf_field() }}
                            <input type="hidden" class="form-control" value="{{ $campaign->campaign_id }}" name="delete_campaign_id">
                            <button type="submit" id="deleteAllZipcode2OnCampaignForm">submit</button>
                        </form>
                        <!-- End row -->
                        <div class="row">
                            <div class="col-12 text-center">
                                <p class="text-muted" id="pErrorsShown">
                                    @foreach( $errors->all() as $error )
                                        {{ $error }}<br>
                                    @endforeach
                                </p>
                            </div>
                        </div>

                        <style>
                            .switch {
                                position: relative;
                                display: inline-block;
                                width: 60px;
                                height: 34px;
                            }

                            .switch input {
                                opacity: 0;
                                width: 0;
                                height: 0;
                            }

                            .slider {
                                position: absolute;
                                cursor: pointer;
                                top: 0;
                                left: 0;
                                right: 0;
                                bottom: 0;
                                background-color: #ccc;
                                -webkit-transition: .4s;
                                transition: .4s;
                            }

                            .slider:before {
                                position: absolute;
                                content: "";
                                height: 26px;
                                width: 26px;
                                left: 4px;
                                bottom: 4px;
                                background-color: white;
                                -webkit-transition: .4s;
                                transition: .4s;
                            }

                            input:checked + .slider {
                                background-color: #2196F3;
                            }

                            input:focus + .slider {
                                box-shadow: 0 0 1px #2196F3;
                            }

                            input:checked + .slider:before {
                                -webkit-transform: translateX(26px);
                                -ms-transform: translateX(26px);
                                transform: translateX(26px);
                            }

                            /* Rounded sliders */
                            .slider.round {
                                border-radius: 34px;
                            }

                            .slider.round:before {
                                border-radius: 50%;
                            }
                        </style>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- End Of Page 1-->
@endsection

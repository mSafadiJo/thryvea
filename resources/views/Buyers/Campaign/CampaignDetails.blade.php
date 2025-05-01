@extends('layouts.NavBuyerHome')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Campaign: {{ $compaign->campaign_name }} Details</h4>
            </div>
        </div>
    </div>
    <style>
        ul#myTab li.nav-item {
            width: 20%;
            text-align: center;
        }
    </style>
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-lg-12">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item ">
                                <a class="nav-link active show" id="home-tab" data-toggle="tab" href="#details" role="tab" aria-controls="home"
                                   aria-selected="true">Campaign Details</a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link" id="address-tab" data-toggle="tab" href="#address" role="tab" aria-controls="address"
                                   aria-selected="false">Campaign  Target</a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link" id="Timedelivery-tab" data-toggle="tab" href="#Timedelivery" role="tab" aria-controls="Timedelivery"
                                   aria-selected="false">Time Delivery</a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link" id="deliverymethod-tab" data-toggle="tab" href="#deliverymethod" role="tab" aria-controls="deliverymethod"
                                   aria-selected="false">Delivery Method</a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link" id="paid-tab" data-toggle="tab" href="#paid" role="tab" aria-controls="paid"
                                   aria-selected="false">Bid</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade active show" id="details" role="tabpanel" aria-labelledby="home-tab">
                                <div class="row m-t-20">
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="Campaign_name">Campaign Name</label>
                                                    <input type="text" class="form-control" id="Campaign_name" readonly name="Campaign_name" value="{{ $compaign->campaign_name }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="Campaign_name">Campaign Type</label>
                                                    <input type="text" class="form-control" id="username" readonly name="username" value="{{ $compaign->campaign_types_name }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="Campaign_name">Service Name</label>
                                                    <input type="text" class="form-control" id="username" readonly name="username" value="{{ $compaign->service_campaign_name }}">
                                                </div>
                                            </div>
                                        </div>
                                        @if( $compaign->service_campaign_id == 1 )
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label for="number_of_windows_c">Number Of Windows</label>
                                                        <select id="number_of_windows_c" name="number_of_windows_c[]" disabled class="select2 form-control select2-multiple" required="" multiple="multiple" data-placeholder="Choose ...">
                                                            <optgroup label="Number Of Windows">
                                                                @if( !empty($campain_status['numberOfWindows']) )
                                                                    @foreach($campain_status['numberOfWindows'] as $item)
                                                                        @if( empty( $campain_status_selected['numberOfWindowsSelected_arr'] ) )
                                                                            <option value="{{ $item->number_of_windows_c_id }}">{{ $item->number_of_windows_c_type }}</option>
                                                                        @else
                                                                            @if( in_array( $item->number_of_windows_c_id, $campain_status_selected['numberOfWindowsSelected_arr']) )
                                                                                <option value="{{ $item->number_of_windows_c_id }}" selected>{{ $item->number_of_windows_c_type }}</option>
                                                                            @else
                                                                                <option value="{{ $item->number_of_windows_c_id }}">{{ $item->number_of_windows_c_type }}</option>
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <h6>Choose as Shared Budget Details</h6>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="budget">Budget</label>
                                                    <input type="text" class="form-control" id="budget" name="budget" readonly @if( $compaign->campaign_budget ) value="{{ $compaign->campaign_budget }}" @else value="0" @endif>
                                                    </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="budget_period">Period</label>
                                                    @if( !empty($campain_status['periods']) )
                                                        @foreach($campain_status['periods'] as $period)
                                                            @if( $compaign->period_campaign_budget_id == $period->period_campaign_id )
                                                                <input type="text" class="form-control" id="periods" name="periods" readonly value="{{ $period->period_campaign_name }}">
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="numberOfCustomerCampaign">Lead Capacity</label>
                                                    <input type="text" class="form-control" id="numberOfCustomerCampaign" name="numberOfCustomerCampaign" readonly
                                                         @if( $compaign->campaign_count_lead ) value="{{ $compaign->campaign_count_lead }}" @else   value="0" @endif>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="numberOfCustomerCampaign_period">Period</label>
                                                        @if( !empty($campain_status['periods']) )
                                                            @foreach($campain_status['periods'] as $period)
                                                                @if( $compaign->period_campaign_count_lead_id == $period->period_campaign_id )
                                                                    <input type="text" class="form-control" id="numberOfCustomerCampaign" name="numberOfCustomerCampaign" readonly
                                                                           value="{{ $period->period_campaign_name }}">
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <h6>Choose as Exclusive Budget Details</h6>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="budget_exclusive">Budget</label>
                                                    <input type="text" class="form-control" id="budget_exclusive" name="budget_exclusive" readonly placeholder="" required=""
                                                           @if( $compaign->campaign_budget_exclusive ) value="{{ $compaign->campaign_budget_exclusive }}" @else value="0" @endif>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="budget_period_exclusive">Period</label>
                                                    @if( !empty($campain_status['periods']) )
                                                        @foreach($campain_status['periods'] as $period)
                                                            @if( $compaign->period_campaign_budget_id_exclusive == $period->period_campaign_id )
                                                                <input type="text" class="form-control" id="periods" name="periods" readonly value="{{ $period->period_campaign_name }}">
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="numberOfCustomerCampaign_exclusive">Lead Capacity</label>
                                                    <input type="text" class="form-control" id="numberOfCustomerCampaign_exclusive" name="numberOfCustomerCampaign_exclusive" readonly placeholder="" required
                                                           @if( $compaign->campaign_count_lead_exclusive ) value="{{ $compaign->campaign_count_lead_exclusive }}" @else value="0" @endif >
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="numberOfCustomerCampaign_period_exclusive">Period</label>
                                                    @if( !empty($campain_status['periods']) )
                                                        @foreach($campain_status['periods'] as $period)
                                                            @if(  $compaign->period_campaign_count_lead_id_exclusive== $period->period_campaign_id )
                                                                <input type="text" class="form-control" id="numberOfCustomerCampaign" name="numberOfCustomerCampaign" readonly
                                                                       value="{{ $period->period_campaign_name }}">
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="address" role="tabpanel" aria-labelledby="address-tab">
                                <div class="row m-t-20">
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <h6>Choose a place among my working areas</h6>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="state">State</label>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <select id="customPaid" name="customPaid[]" class="select2 form-control select2-multiple" required="" multiple="multiple" data-placeholder="" disabled>
                                                                <optgroup label="Custom Bid">
                                                                    @if( !empty($address['states_in_campaign']) )
                                                                        @foreach($address['states_in_campaign'] as $state)
                                                                            <option value="{{ $state->state_code }}" selected>{{ $state->state_code }}</option>
                                                                        @endforeach
                                                                    @endif
                                                                </optgroup>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="county">County</label>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <select id="customPaid" name="customPaid[]" class="select2 form-control select2-multiple" required="" multiple="multiple" data-placeholder="" disabled>
                                                                <optgroup label="Custom Bid">
                                                                    @if( !empty($address['counties_in_campaign']) )
                                                                        @foreach($address['counties_in_campaign'] as $county)
                                                                            <option value="{{ $county->county_name }}" selected>{{ $county->county_name }}</option>
                                                                        @endforeach
                                                                    @endif
                                                                </optgroup>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="city">City</label>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <select id="customPaid" name="customPaid[]" class="select2 form-control select2-multiple" required="" multiple="multiple" data-placeholder="" disabled>
                                                                <optgroup label="Custom Bid">
                                                                    @if( !empty($address['cities_in_campaign']) )
                                                                        @foreach($address['cities_in_campaign'] as $city)
                                                                            <option value="{{ $city->city_name }}" selected>{{ $city->city_name }}</option>
                                                                        @endforeach
                                                                    @endif
                                                                </optgroup>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="zipcode">Zip-Code</label>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <select id="customPaid" name="customPaid[]" class="select2 form-control select2-multiple" required="" multiple="multiple" data-placeholder="" disabled>
                                                                <optgroup label="Custom Bid">
                                                                    @if( !empty($address['zipcods_in_campaign']) )
                                                                        @foreach($address['zipcods_in_campaign'] as $zipcode)
                                                                            <option value="{{ $zipcode }}" selected>{{ $zipcode }}</option>
                                                                        @endforeach
                                                                    @endif
                                                                </optgroup>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="distance_area">Distance Area</label>
                                                    <input type="text" class="form-control" id="distance_area" readonly name="distance_area" value="{{ $compaign->campaign_distance_area }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <h6>Places that aren't among my working area</h6>
                                            </div>
                                            {{--<div class="col-sm-6">--}}
                                            {{--<div class="form-group">--}}
                                            {{--<label for="state_expect">State</label>--}}
                                            {{--<div class="row">--}}
                                            {{--<div class="col-sm-12">--}}
                                            {{--<ul>--}}
                                            {{--@if( !empty($address['states_not_in_campaign']) )--}}
                                            {{--@foreach($address['states_not_in_campaign'] as $state)--}}
                                            {{--<li>{{ $state->state_name }} => {{ $state->state_code }}</li>--}}
                                            {{--@endforeach--}}
                                            {{--@endif--}}
                                            {{--</ul>--}}
                                            {{--</div>--}}
                                            {{--</div>--}}
                                            {{--</div>--}}
                                            {{--</div>--}}
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="county_expect">County</label>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <select id="customPaid" name="customPaid[]" class="select2 form-control select2-multiple" required="" multiple="multiple" data-placeholder="" disabled>
                                                                <optgroup label="Custom Bid">
                                                                    @if( !empty($address['counties_not_in_campaign']) )
                                                                        @foreach($address['counties_not_in_campaign'] as $county)
                                                                            <option value="{{ $county->county_name }}" selected>{{ $county->county_name }}</option>
                                                                        @endforeach
                                                                    @endif
                                                                </optgroup>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="city_expect">City</label>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <select id="customPaid" name="customPaid[]" class="select2 form-control select2-multiple" required="" multiple="multiple" data-placeholder="" disabled>
                                                                <optgroup label="Custom Bid">
                                                                    @if( !empty($address['cities_not_in_campaign']) )
                                                                        @foreach($address['cities_not_in_campaign'] as $city)
                                                                            <option value="{{ $city->city_name }}" selected>{{ $city->city_name }}</option>
                                                                        @endforeach
                                                                    @endif
                                                                </optgroup>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="zipcode_expect">Zip-Code</label>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <select id="customPaid" name="customPaid[]" class="select2 form-control select2-multiple" required="" multiple="multiple" data-placeholder="" disabled>
                                                                <optgroup label="Custom Bid">
                                                                    @if( !empty($address['zipcods_not_in_campaign']) )
                                                                        @foreach($address['zipcods_not_in_campaign'] as $zipcode)
                                                                            <option value="{{ $zipcode }}" selected>{{ $zipcode }}</option>
                                                                        @endforeach
                                                                    @endif
                                                                </optgroup>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{--<div class="row">--}}
                                            {{--<div class="col-sm-12">--}}
                                                {{--<div class="form-group">--}}
                                                    {{--<label for="distance_area">Distance Area</label>--}}
                                                    {{--<input type="text" class="form-control" id="distance_area" readonly name="distance_area" value="{{ $compaign->campaign_distance_area_expect }}">--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="Timedelivery" role="tabpanel" aria-labelledby="Timedelivery-tab">
                                <div class="row m-t-20">
                                    <div class="col-sm-12">
                                        <div class="row timemargin">
                                            <div class="col-sm-1">
                                                <label for="state">Timezone</label>
                                            </div>
                                            <div class="col-sm-5">
                                                <select class="select2 form-control" required name="timezone" id="timezone" data-placeholder="Choose ..." disabled>
                                                    <optgroup label="Timezone">
                                                        <option value="5" @if( $compaign->campaign_time_delivery_timezone == 5 ) selected @endif>Eastern Time</option>
                                                        <option value="6" @if( $compaign->campaign_time_delivery_timezone == 6 ) selected @endif>Central Time</option>
                                                        <option value="7" @if( $compaign->campaign_time_delivery_timezone == 7 ) selected @endif>Mountain Time</option>
                                                        <option value="8" @if( $compaign->campaign_time_delivery_timezone == 8 ) selected @endif>Pacific Time</option>
                                                    </optgroup>
                                                </select>
                                            </div>
                                            <div class="col-sm-6">
                                            </div>
                                        </div>
                                        <div class="row timemargin">
                                            <div class="col-sm-1">
                                                <label for="sun">Sunday: </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control timepicker" id="starttime-Sun" required name="starttime_Sun" placeholder="Start Time" disabled
                                                       value="@if( $compaign->start_sun != '00:00:00' ) {{ date('h:i A', strtotime($compaign->start_sun)) }} @endif">
                                            </div> <div class="col-sm-2">
                                                <input type="text" class="form-control timepicker" id="endtime-Sun" required name="endtime_Sun" placeholder="End Time" disabled
                                                       value="@if( $compaign->end_sun != '00:00:00' ) {{ date('h:i A', strtotime($compaign->end_sun)) }} @endif">
                                            </div>
                                            <div class="col-sm-2">
                                                <label class="container-dayoff">Day Off
                                                    <input type="checkbox" id="offday-Sun" name="offday_Sun" value="1" disabled
                                                           @if( $compaign->status_sun == 1 )
                                                           checked
                                                            @endif>
                                                    <span class="checkmark-dayoff"></span>
                                                </label>
                                            </div>
                                            <div class="col-sm-5">
                                            </div>
                                        </div>
                                        <div class="row timemargin">
                                            <div class="col-sm-1">
                                                <label for="sun">Monday: </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control timepicker" id="starttime-Mon" required name="starttime_Mon" placeholder="Start Time" disabled
                                                       value="@if( $compaign->start_mon != '00:00:00' ) {{ date('h:i A', strtotime($compaign->start_mon)) }} @endif">
                                            </div> <div class="col-sm-2">
                                                <input type="text" class="form-control timepicker" id="endtime-Mon" required name="endtime_Mon" placeholder="End Time" disabled
                                                       value="@if( $compaign->end_mon != '00:00:00' ) {{ date('h:i A', strtotime($compaign->end_mon)) }} @endif">
                                            </div>
                                            <div class="col-sm-2">
                                                <label class="container-dayoff">Day Off
                                                    <input type="checkbox" id="offday-Mon" name="offday_Mon" value="1" disabled
                                                           @if( $compaign->status_mon == 1 )
                                                           checked
                                                            @endif >
                                                    <span class="checkmark-dayoff"></span>
                                                </label>
                                            </div>
                                            <div class="col-sm-5">
                                            </div>
                                        </div>
                                        <div class="row timemargin">
                                            <div class="col-sm-1">
                                                <label for="sun">Tuesday: </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control timepicker" id="starttime-Tus" required name="starttime_Tus" placeholder="Start Time" disabled
                                                       value="@if( $compaign->start_tus != '00:00:00' ) {{ date('h:i A', strtotime($compaign->start_tus)) }} @endif">
                                            </div> <div class="col-sm-2">
                                                <input type="text" class="form-control timepicker" id="endtime-Tus" required name="endtime_Tus" placeholder="End Time" disabled
                                                       value="@if( $compaign->end_tus != '00:00:00' ) {{ date('h:i A', strtotime($compaign->end_tus)) }} @endif">
                                            </div>
                                            <div class="col-sm-2">
                                                <label class="container-dayoff">Day Off
                                                    <input type="checkbox" id="offday-Tus" name="offday_Tus" value="1" disabled
                                                           @if( $compaign->status_tus == 1 )
                                                           checked
                                                            @endif >
                                                    <span class="checkmark-dayoff"></span>
                                                </label>
                                            </div>
                                            <div class="col-sm-5">
                                            </div>
                                        </div>
                                        <div class="row timemargin">
                                            <div class="col-sm-1">
                                                <label for="sun">Wednesday: </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control timepicker" id="starttime-Wen" required name="starttime_Wen" placeholder="Start Time" disabled
                                                       value="@if( $compaign->start_wen != '00:00:00' ) {{ date('h:i A', strtotime($compaign->start_wen)) }} @endif">
                                            </div> <div class="col-sm-2">
                                                <input type="text" class="form-control timepicker" id="endtime-Wen" required name="endtime_Wen" placeholder="End Time" disabled
                                                       value="@if( $compaign->end_wen != '00:00:00' ) {{ date('h:i A', strtotime($compaign->end_wen)) }} @endif">
                                            </div>
                                            <div class="col-sm-2">
                                                <label class="container-dayoff">Day Off
                                                    <input type="checkbox" id="offday-Wen" name="offday_Wen" value="1" disabled
                                                           @if( $compaign->status_wen == 1 )
                                                           checked
                                                            @endif >
                                                    <span class="checkmark-dayoff"></span>
                                                </label>
                                            </div>
                                            <div class="col-sm-5">
                                            </div>
                                        </div>
                                        <div class="row timemargin">
                                            <div class="col-sm-1">
                                                <label for="sun">Thursday: </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control timepicker" id="starttime-Thr" required name="starttime_Thr" placeholder="Start Time" disabled
                                                       value="@if( $compaign->start_thr != '00:00:00' ) {{ date('h:i A', strtotime($compaign->start_thr)) }} @endif">
                                            </div> <div class="col-sm-2">
                                                <input type="text" class="form-control timepicker" id="endtime-Thr" required name="endtime_Thr" placeholder="End Time" disabled
                                                       value="@if( $compaign->end_thr != '00:00:00' ) {{ date('h:i A', strtotime($compaign->end_thr)) }} @endif">
                                            </div>
                                            <div class="col-sm-2">
                                                <label class="container-dayoff">Day Off
                                                    <input type="checkbox" id="offday-Thr" name="offday_Thr" value="1" disabled
                                                           @if( $compaign->status_thr == 1 )
                                                           checked
                                                            @endif >
                                                    <span class="checkmark-dayoff"></span>
                                                </label>
                                            </div>
                                            <div class="col-sm-5">
                                            </div>
                                        </div>
                                        <div class="row timemargin">
                                            <div class="col-sm-1">
                                                <label for="sun">Friday: </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control timepicker" id="starttime-fri" required name="starttime_fri" placeholder="Start Time" disabled
                                                       value="@if( $compaign->start_fri != '00:00:00' ) {{ date('h:i A', strtotime($compaign->start_fri)) }} @endif">
                                            </div> <div class="col-sm-2">
                                                <input type="text" class="form-control timepicker" id="endtime-fri" required name="endtime_fri" placeholder="End Time" disabled
                                                       value="@if( $compaign->end_fri != '00:00:00' ) {{ date('h:i A', strtotime($compaign->end_fri)) }} @endif">
                                            </div>
                                            <div class="col-sm-2">
                                                <label class="container-dayoff">Day Off
                                                    <input type="checkbox" id="offday-fri" name="offday_fri" value="1" disabled
                                                           @if( $compaign->status_fri == 1 )
                                                           checked
                                                            @endif >
                                                    <span class="checkmark-dayoff"></span>
                                                </label>
                                            </div>
                                            <div class="col-sm-5">
                                            </div>
                                        </div>
                                        <div class="row timemargin">
                                            <div class="col-sm-1">
                                                <label for="sun">Saturday: </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control timepicker" id="starttime-sat" required name="starttime_sat" placeholder="Start Time" disabled
                                                       value="@if( $compaign->start_sat != '00:00:00' ) {{ date('h:i A', strtotime($compaign->start_sat)) }} @endif">
                                            </div> <div class="col-sm-2">
                                                <input type="text" class="form-control timepicker" id="endtime-sat" required name="endtime_sat" placeholder="End Time" disabled
                                                       value="@if( $compaign->end_sat != '00:00:00') {{ date('h:i A', strtotime($compaign->end_sat)) }} @endif">
                                            </div>
                                            <div class="col-sm-2">
                                                <label class="container-dayoff">Day Off
                                                    <input type="checkbox" id="offday-sat" name="offday_sat" value="1" disabled
                                                           @if( $compaign->status_sat == 1 )
                                                           checked
                                                            @endif >
                                                    <span class="checkmark-dayoff"></span>
                                                </label>
                                            </div>
                                            <div class="col-sm-5">
                                            </div>
                                        </div>
                                        <div class="row timemargin">
                                            <div class="col-sm-3">
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="checkbox" style="display:none;" id="fordisabledData" value="1" checked>
                                                <label class="col-md-6 control-label text-center">Availability 24/7</label>
                                                <label class="col-md-6 switch">
                                                    <input type="checkbox" name="hourin7days" id="24hourin7days" value="1" disabled
                                                    <?php
                                                        if( $compaign->campaign_time_delivery_status !== null){
                                                            if($compaign->campaign_time_delivery_status == 1){
                                                                echo "checked";
                                                            }
                                                        }
                                                        ?>>
                                                    <span class="slider round"></span>
                                                </label>
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
                                            <div class="col-sm-5">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="deliverymethod" role="tabpanel" aria-labelledby="deliverymethod-tab">
                                <div class="row m-t-20">
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="deliveryMethod">Delivery Method</label>
                                                    <select id="deliveryMethod" name="deliveryMethod[]" class="select2 form-control select2-multiple" required="" multiple="multiple" disabled data-placeholder="Choose ...">
                                                        <optgroup label="Delivery Method">
                                                            @if( !empty($campain_status['deliveryMethods']) )
                                                                @foreach($campain_status['deliveryMethods'] as $deliveryMethod)
                                                                    @if( empty($campain_status_selected['deliveryMethodsSelected_arr']) )
                                                                        <option value="{{ $deliveryMethod->delivery_Method_id }}">{{ $deliveryMethod->delivery_Method_type }}</option>
                                                                    @else
                                                                        @if( in_array( $deliveryMethod->delivery_Method_id, $campain_status_selected['deliveryMethodsSelected_arr'] ) )
                                                                            <option value="{{ $deliveryMethod->delivery_Method_id }}" selected>{{ $deliveryMethod->delivery_Method_type }}</option>
                                                                        @else
                                                                            <option value="{{ $deliveryMethod->delivery_Method_id }}">{{ $deliveryMethod->delivery_Method_type }}</option>
                                                                        @endif
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </optgroup>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        {{--email design--}}

                                        <div class="row email-phone-Dynamic">
                                            <div class="col-md-6">
                                                <div class="card h-100 crm email-card">
                                                    <h2>Emails</h2>
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label for="First Email">First Email</label>
                                                            <input type="email" name="FirstEmail" class="form-control" id="FirstEmail" aria-describedby="FirstEmail"
                                                                   @if(empty($compaign->email1))
                                                                   value=""
                                                                   @else
                                                                   value="{{$compaign->email1}}"
                                                                   @endif
                                                                   disabled
                                                            >
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="Second Email">Second Email</label>
                                                            <input type="email" name="SecondEmail" class="form-control" id="SecondEmail" aria-describedby="SecondEmail"
                                                                   @if(empty($compaign->email2))
                                                                   value=""
                                                                   @else
                                                                   value="{{$compaign->email2}}"
                                                                   @endif
                                                                   disabled
                                                            >
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="Third Email">Third Email</label>
                                                            <input type="email" name="ThirdEmail" class="form-control" id="ThirdEmail" aria-describedby="ThirdEmail"
                                                                   @if(empty($compaign->email3))
                                                                   value=""
                                                                   @else
                                                                   value="{{$compaign->email3}}"
                                                                   @endif
                                                                   disabled
                                                            >
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="card h-100 crm phone-card">
                                                    <h2>SMS</h2>
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label for="First Phone">First Phone Number</label>
                                                            <input type="text" name="FirstPhone" class="form-control" id="FirstPhone" aria-describedby="FirstPhone"
                                                                   @if(empty($compaign->phone1))
                                                                   value=""
                                                                   @else
                                                                   value="{{$compaign->phone1}}"
                                                                   @endif
                                                                   disabled
                                                            >
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="Second Phone">Second Phone Number</label>
                                                            <input type="text" name="SecondPhone" class="form-control" id="SecondPhone" aria-describedby="SecondPhone"
                                                                   @if(empty($compaign->phone2))
                                                                   value=""
                                                                   @else
                                                                   value="{{$compaign->phone2}}"
                                                                   @endif
                                                                   disabled
                                                            >
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="Third Phone">Third Phone Number</label>
                                                            <input type="text" name="ThirdPhone" class="form-control" id="ThirdPhone" aria-describedby="ThirdPhone"
                                                                   @if(empty($compaign->phone3))
                                                                   value=""
                                                                   @else
                                                                   value="{{$compaign->phone3}}"
                                                                   @endif
                                                                   disabled
                                                            >
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>

                                        {{--end email design--}}

                                        {{--crm design--}}
                                        <div class="row crmDynamic">
                                            <div class="col-md-6">
                                                <div class="card h-100 crm">
                                                    <h2>CallTools</h2>
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label for="Api Key">Api Key</label>
                                                            <input type="text" name="ApiKey" class="form-control" id="ApiKey" aria-describedby="ApiKey"
                                                                   @if(empty($callToolsTabel->api_key))
                                                                   value=""
                                                                   @else
                                                                   value="{{$callToolsTabel->api_key}}"
                                                                   @endif
                                                                   disabled
                                                            >
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="Api Key">File ID</label>
                                                            <input type="text" name="FileId" class="form-control" id="FileId" aria-describedby="FileId"
                                                                   @if(empty($callToolsTabel->file_id))
                                                                   value=""
                                                                   @else
                                                                   value="{{$callToolsTabel->file_id}}"
                                                                   @endif
                                                                   disabled
                                                            >
                                                        </div>
                                                        <label class="col-md-6 switch crmSwitch">
                                                            <input type="checkbox" id="coolToolsToggle" value="1" disabled>
                                                            <span class="slider round coolToolsToggle "></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="card h-100 crm">
                                                    <h2>Five9</h2>
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label for="Five9 Domian">Five9 Domian</label>
                                                            <input type="text" class="form-control" name="Five9Domian" id="Five9Domian" aria-describedby="Five9Domian"
                                                                   @if(empty($five9Tabel->five9_domian))
                                                                   value=""
                                                                   @else
                                                                   value="{{$five9Tabel->five9_domian}}"
                                                                   @endif
                                                                   disabled
                                                            >
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="Five9 List">Five9 List</label>
                                                            <input type="text" class="form-control" name="Five9List" id="Five9List" aria-describedby="Five9List"
                                                                   @if(empty($five9Tabel->five9_list))
                                                                   value=""
                                                                   @else
                                                                   value="{{$five9Tabel->five9_list}}"
                                                                   @endif
                                                                   disabled
                                                            >
                                                        </div>

                                                        <label class="col-md-6 switch crmSwitch">
                                                            <input type="checkbox" id="five9Toggle" value="1" disabled>
                                                            <span class="slider round five9Toggle "></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="card h-100 crm">
                                                    <h2>Leads Pedia</h2>
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label for="Leads Pedia Url">Leads Pedia Url</label>
                                                            <input type="text" class="form-control" name="LeadsPediaUrl" id="LeadsPediaUrl" aria-describedby="LeadsPediaUrl"
                                                                   @if(empty($LeadsPediaTabel->leads_pedia_url))
                                                                   value=""
                                                                   @else
                                                                   value="{{$LeadsPediaTabel->leads_pedia_url}}"
                                                                   @endif
                                                                   disabled
                                                            >
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="Campaign ID">Campaign ID</label>
                                                            <input type="text" class="form-control" name="IP_Campaign_ID" id="IPCampaignID" aria-describedby="IPCampaignID"
                                                                   @if(empty($LeadsPediaTabel->IP_Campaign_ID))
                                                                   value=""
                                                                   @else
                                                                   value="{{$LeadsPediaTabel->IP_Campaign_ID}}"
                                                                   @endif
                                                                   disabled
                                                            >
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="Campine Key">Campaign Key</label>
                                                            <input type="text" class="form-control" name="CampineKey" id="CampineKey" aria-describedby="CampineKey"
                                                                   @if(empty($LeadsPediaTabel->campine_key))
                                                                   value=""
                                                                   @else
                                                                   value="{{$LeadsPediaTabel->campine_key}}"
                                                                   @endif
                                                                   disabled
                                                            >
                                                        </div>

                                                        <label class="col-md-6 switch crmSwitch">
                                                            <input type="checkbox" id="LeadsPediaToggle" value="1" disabled>
                                                            <span class="slider round LeadsPediaToggle "></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>


                                        {{--end crm design--}}
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="paid" role="tabpanel" aria-labelledby="paid-tab">
                                <div class="row m-t-20">
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="propertytype">Property Type</label>
                                                    <select id="propertytype" name="propertytype[]" class="select2 form-control select2-multiple" required="" multiple="multiple" data-placeholder="Choose ..." disabled>
                                                        <optgroup label="Property Type">
                                                            @if( !empty($campain_status['property_type_campains']) )
                                                                @foreach($campain_status['property_type_campains'] as $property_type_campain)
                                                                    @if( empty( $campain_status_selected['property_type_campainsSelected_arr'] ) )
                                                                        <option value="{{ $property_type_campain->property_type_campaign_id }}">{{ $property_type_campain->property_type_campaign }}</option>
                                                                    @else
                                                                        @if( in_array( $property_type_campain->property_type_campaign_id, $campain_status_selected['property_type_campainsSelected_arr']) )
                                                                            <option value="{{ $property_type_campain->property_type_campaign_id }}" selected>{{ $property_type_campain->property_type_campaign }}</option>
                                                                        @else
                                                                            <option value="{{ $property_type_campain->property_type_campaign_id }}">{{ $property_type_campain->property_type_campaign }}</option>
                                                                        @endif
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </optgroup>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="Installings">Type Of Service</label>
                                                    <select id="Installings" name="Installings[]" class="select2 form-control select2-multiple" required="" multiple="multiple" data-placeholder="Choose ..." disabled>
                                                        <optgroup label="Type Of Service">
                                                            @if( !empty($campain_status['campain_inistallings']) )
                                                                @foreach($campain_status['campain_inistallings'] as $campain_inistalling)
                                                                    @if( empty( $campain_status_selected['campain_inistallingsSelected_arr'] ) )
                                                                        <option value="{{ $campain_inistalling->installing_type_campaign_id }}">{{ $campain_inistalling->installing_type_campaign }}</option>
                                                                    @else
                                                                        @if( in_array( $campain_inistalling->installing_type_campaign_id, $campain_status_selected['campain_inistallingsSelected_arr']) )
                                                                            <option value="{{ $campain_inistalling->installing_type_campaign_id }}" selected>{{ $campain_inistalling->installing_type_campaign }}</option>
                                                                        @else
                                                                            <option value="{{ $campain_inistalling->installing_type_campaign_id }}">{{ $campain_inistalling->installing_type_campaign }}</option>
                                                                        @endif
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </optgroup>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="customPaid">Custom Bid</label>
                                                    <select id="customPaid" name="customPaid[]" class="select2 form-control select2-multiple" required="" multiple="multiple" data-placeholder="Choose ..." disabled>
                                                        <optgroup label="Custom Bid">
                                                            @if( !empty($campain_status['custom_paid_campains']) )
                                                                @foreach($campain_status['custom_paid_campains'] as $custom_paid_campain)
                                                                    @if( empty( $campain_status_selected['custom_paid_campainsSelected_arr'] ) )
                                                                        <option value="{{ $custom_paid_campain->custom_paid_campaign_id }}">{{ $custom_paid_campain->custom_paid_campaign }}</option>
                                                                    @else
                                                                        @if( in_array( $custom_paid_campain->custom_paid_campaign_id, $campain_status_selected['custom_paid_campainsSelected_arr']) )
                                                                            <option value="{{ $custom_paid_campain->custom_paid_campaign_id }}" selected>{{ $custom_paid_campain->custom_paid_campaign }}</option>
                                                                        @else
                                                                            <option value="{{ $custom_paid_campain->custom_paid_campaign_id }}">{{ $custom_paid_campain->custom_paid_campaign }}</option>
                                                                        @endif
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </optgroup>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="homeowned">Owner</label>
                                                    <select id="homeowned" name="homeowned[]" class="select2 form-control select2-multiple" required="" multiple="multiple" data-placeholder="Choose ..." disabled>
                                                        <optgroup label="Owner">
                                                            @if( empty( $campain_status_selected['homeOwnedSelected_arr'] ) )
                                                                <option value="1">Yes</option>
                                                                <option value="0">No</option>
                                                            @else
                                                                <option value="1" <?php if( in_array('1', $campain_status_selected['homeOwnedSelected_arr'] ) ) { echo "selected"; } ?> >Yes</option>
                                                                <option value="0" <?php if( in_array('0', $campain_status_selected['homeOwnedSelected_arr'] ) ) { echo "selected"; } ?> >No</option>
                                                            @endif
                                                        </optgroup>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        @if( $compaign->service_campaign_id == 2 )
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label for="solorBill">Customer's Average monthly electricity bill</label>
                                                        <select id="solorBill" name="solorBill[]" disabled class="select2 form-control select2-multiple" required="" multiple="multiple" data-placeholder="Choose ...">
                                                            <optgroup label="Customer's Average monthly electricity bill">
                                                                @if( !empty($campain_status['solorBill']) )
                                                                    @foreach($campain_status['solorBill'] as $item)
                                                                        @if( empty( $campain_status_selected['solorBill_arr'] ) )
                                                                            <option value="{{ $item->lead_avg_money_electicity_list_id }}">{{ $item->lead_avg_money_electicity_list_name }}</option>
                                                                        @else
                                                                            @if( in_array( $item->lead_avg_money_electicity_list_id, $campain_status_selected['solorBill_arr']) )
                                                                                <option value="{{ $item->lead_avg_money_electicity_list_id }}" selected>{{ $item->lead_avg_money_electicity_list_name }}</option>
                                                                            @else
                                                                                <option value="{{ $item->lead_avg_money_electicity_list_id }}">{{ $item->lead_avg_money_electicity_list_name }}</option>
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label for="Solarpowersolution">Kind of Solar power solution</label>
                                                        <select id="Solarpowersolution" disabled name="Solarpowersolution[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                                                            <optgroup label="Kind of Solar power solution">
                                                                @if( !empty($campain_status['lead_solor_solution_list']) )
                                                                    @foreach($campain_status['lead_solor_solution_list'] as $item)
                                                                        @if( empty( $campain_status_selected['campaign__solarpowersolution_arr'] ) )
                                                                            <option value="{{ $item->lead_solor_solution_list_id }}">{{ $item->lead_solor_solution_list_name }}</option>
                                                                        @else
                                                                            @if( in_array( $item->lead_solor_solution_list_id, $campain_status_selected['campaign__solarpowersolution_arr']) )
                                                                                <option value="{{ $item->lead_solor_solution_list_id }}" selected>{{ $item->lead_solor_solution_list_name }}</option>
                                                                            @else
                                                                                <option value="{{ $item->lead_solor_solution_list_id }}">{{ $item->lead_solor_solution_list_name }}</option>
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label for="RoofShade">Sun exposure, Roof Shade</label>
                                                        <select id="RoofShade" disabled name="RoofShade[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                                                            <optgroup label="Sun exposure, Roof Shade">
                                                                @if( !empty($campain_status['lead_solor_sun_expouser_list']) )
                                                                    @foreach($campain_status['lead_solor_sun_expouser_list'] as $item)
                                                                        @if( empty( $campain_status_selected['campaign__roof_shade_arr'] ) )
                                                                            <option value="{{ $item->lead_solor_sun_expouser_list_id }}">{{ $item->lead_solor_sun_expouser_list_name }}</option>
                                                                        @else
                                                                            @if( in_array( $item->lead_solor_sun_expouser_list_id, $campain_status_selected['campaign__roof_shade_arr']) )
                                                                                <option value="{{ $item->lead_solor_sun_expouser_list_id }}" selected>{{ $item->lead_solor_sun_expouser_list_name }}</option>
                                                                            @else
                                                                                <option value="{{ $item->lead_solor_sun_expouser_list_id }}">{{ $item->lead_solor_sun_expouser_list_name }}</option>
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif( $compaign->service_campaign_id == 3 )
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="securityInstalling">Installation Preferences</label>
                                                        <select id="securityInstalling" name="securityInstalling[]" disabled class="select2 form-control select2-multiple" required="" multiple="multiple" data-placeholder="Choose ...">
                                                            <optgroup label="Installation Preferences">
                                                                @if( !empty($campain_status['securityInstalling']) )
                                                                    @foreach($campain_status['securityInstalling'] as $item)
                                                                        @if( empty( $campain_status_selected['securityInstalling_arr'] ) )
                                                                            <option value="{{ $item->lead_installation_preferences_id }}">{{ $item->lead_installation_preferences_name }}</option>
                                                                        @else
                                                                            @if( in_array( $item->lead_installation_preferences_id, $campain_status_selected['securityInstalling_arr']) )
                                                                                <option value="{{ $item->lead_installation_preferences_id }}" selected>{{ $item->lead_installation_preferences_name }}</option>
                                                                            @else
                                                                                <option value="{{ $item->lead_installation_preferences_id }}">{{ $item->lead_installation_preferences_name }}</option>
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="ExistingMonitoringSystem">Customer Has An Existing Alarm And/ Or Monitoring System</label>
                                                        <select id="ExistingMonitoringSystem" disabled name="ExistingMonitoringSystem[]" class="select2 form-control select2-multiple" required="" multiple="multiple" data-placeholder="Choose ...">
                                                            <optgroup label="Customer Has An Existing Alarm And/ Or Monitoring System">
                                                                @if( empty( $campain_status_selected['campaign__existing_monitoring_system_arr'] ) )
                                                                    <option value="1">Yes</option>
                                                                    <option value="0">No</option>
                                                                @else
                                                                    <option value="1" <?php if( in_array('1', $campain_status_selected['campaign__existing_monitoring_system_arr'] ) ) { echo "selected"; } ?> >Yes</option>
                                                                    <option value="0" <?php if( in_array('0', $campain_status_selected['campaign__existing_monitoring_system_arr'] ) ) { echo "selected"; } ?> >No</option>
                                                                @endif
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif( $compaign->service_campaign_id == 4 )
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label for="flooringtype">Type of flooring</label>
                                                        <select id="flooringtype" name="flooringtype[]" disabled class="select2 form-control select2-multiple" required="" multiple="multiple" data-placeholder="Choose ...">
                                                            <optgroup label="Type of flooring">
                                                                @if( !empty($campain_status['flooringtype']) )
                                                                    @foreach($campain_status['flooringtype'] as $item)
                                                                        @if( empty( $campain_status_selected['flooringtype_arr'] ) )
                                                                            <option value="{{ $item->lead_type_of_flooring_id }}">{{ $item->lead_type_of_flooring_name }}</option>
                                                                        @else
                                                                            @if( in_array( $item->lead_type_of_flooring_id, $campain_status_selected['flooringtype_arr']) )
                                                                                <option value="{{ $item->lead_type_of_flooring_id }}" selected>{{ $item->lead_type_of_flooring_name }}</option>
                                                                            @else
                                                                                <option value="{{ $item->lead_type_of_flooring_id }}">{{ $item->lead_type_of_flooring_name }}</option>
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif( $compaign->service_campaign_id == 5 )
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label for="lead_walk_in_tub">What kind of Walk-In Tubs?</label>
                                                        <select id="lead_walk_in_tub" name="lead_walk_in_tub[]" disabled class="select2 form-control select2-multiple" required="" multiple="multiple" data-placeholder="Choose ...">
                                                            <optgroup label="What kind of Walk-In Tubs?">
                                                                @if( !empty($campain_status['lead_walk_in_tub']) )
                                                                    @foreach($campain_status['lead_walk_in_tub'] as $item)
                                                                        @if( empty( $campain_status_selected['lead_walk_in_tub_arr'] ) )
                                                                            <option value="{{ $item->lead_walk_in_tub_id }}">{{ $item->lead_walk_in_tub_name }}</option>
                                                                        @else
                                                                            @if( in_array( $item->lead_walk_in_tub_id, $campain_status_selected['lead_walk_in_tub_arr']) )
                                                                                <option value="{{ $item->lead_walk_in_tub_id }}" selected>{{ $item->lead_walk_in_tub_name }}</option>
                                                                            @else
                                                                                <option value="{{ $item->lead_walk_in_tub_id }}">{{ $item->lead_walk_in_tub_name }}</option>
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif( $compaign->service_campaign_id == 6 )
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label for="roofingtype">Type of roofing</label>
                                                        <select id="roofingtype" name="roofingtype[]" disabled class="select2 form-control select2-multiple" required="" multiple="multiple" data-placeholder="Choose ...">
                                                            <optgroup label="Type of roofing">
                                                                @if( !empty($campain_status['roofingtype']) )
                                                                    @foreach($campain_status['roofingtype'] as $item)
                                                                        @if( empty( $campain_status_selected['roofingtype_arr'] ) )
                                                                            <option value="{{ $item->lead_type_of_roofing_id }}">{{ $item->lead_type_of_roofing_name }}</option>
                                                                        @else
                                                                            @if( in_array( $item->lead_type_of_roofing_id, $campain_status_selected['roofingtype_arr']) )
                                                                                <option value="{{ $item->lead_type_of_roofing_id }}" selected>{{ $item->lead_type_of_roofing_name }}</option>
                                                                            @else
                                                                                <option value="{{ $item->lead_type_of_roofing_id }}">{{ $item->lead_type_of_roofing_name }}</option>
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif( $compaign->service_campaign_id == 7 )
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label for="roofingtype">Type of siding<span class="requiredFields">*</span></label>
                                                        <select id="sidingtype" name="sidingtype[]" disabled class="select2 form-control select2-multiple" multiple="multiple" data-placeholder="Choose ...">
                                                            <optgroup label="Type of siding">
                                                                @if( !empty($campain_status['type_of_siding_lead']) )
                                                                    @foreach($campain_status['type_of_siding_lead'] as $item)
                                                                        @if( empty( $campain_status_selected['type_of_siding_lead_arr'] ) )
                                                                            <option value="{{ $item->type_of_siding_lead_id }}">{{ $item->type_of_siding_lead_type }}</option>
                                                                        @else
                                                                            @if( in_array( $item->type_of_siding_lead_id, $campain_status_selected['type_of_siding_lead_arr'])) )
                                                                            <option value="{{ $item->type_of_siding_lead_id }}" selected>{{ $item->type_of_siding_lead_type }}</option>
                                                                            @else
                                                                                <option value="{{ $item->type_of_siding_lead_id }}">{{ $item->type_of_siding_lead_type }}</option>
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif( $compaign->service_campaign_id == 8 )
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="kitchen_service">What services of kitchen remodeling you do?<span class="requiredFields">*</span></label>
                                                        <select id="kitchen_service" disabled name="kitchen_service[]" class="select2 form-control select2-multiple" multiple="multiple" data-placeholder="Choose ...">
                                                            <optgroup label="What services of kitchen remodeling you do?">
                                                                @if( !empty( $campain_status['service_kitchen_lead'] ) )
                                                                    @foreach($campain_status['service_kitchen_lead'] as $item)
                                                                        @if( empty( $campain_status_selected['campaign_kitchen_service_arr'] ) )
                                                                            <option value="{{ $item->service_kitchen_lead_id }}">{{ $item->service_kitchen_lead_type }}</option>
                                                                        @else
                                                                            @if( in_array( $item->service_kitchen_lead_id, $campain_status_selected['campaign_kitchen_service_arr']) )
                                                                                <option value="{{ $item->service_kitchen_lead_id }}" selected>{{ $item->service_kitchen_lead_type }}</option>
                                                                            @else
                                                                                <option value="{{ $item->service_kitchen_lead_id }}">{{ $item->service_kitchen_lead_type }}</option>
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="removing_adding_walls">Are you able to demolish/build walls?<span class="requiredFields">*</span></label>
                                                        <select id="removing_adding_walls" disabled name="removing_adding_walls[]" class="select2 form-control select2-multiple" multiple="multiple" data-placeholder="Choose ...">
                                                            <optgroup label="Are you able to demolish/build walls?">
                                                                @if( empty( $campain_status_selected['campaign_kitchen_r_a_walls_arr'] ) )
                                                                    <option value="1">Yes</option>
                                                                    <option value="0">No</option>
                                                                @else
                                                                    <option value="1" <?php if( in_array('1', $campain_status_selected['campaign_kitchen_r_a_walls_arr'] ) ) { echo "selected"; } ?> >Yes</option>
                                                                    <option value="0" <?php if( in_array('0', $campain_status_selected['campaign_kitchen_r_a_walls_arr'] ) ) { echo "selected"; } ?> >No</option>
                                                                @endif
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif( $compaign->service_campaign_id == 9 )
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label for="bathroom_service">What services of Bathroom remodeling you do?<span class="requiredFields">*</span></label>
                                                        <select id="bathroom_service" disabled name="bathroom_service[]" class="select2 form-control select2-multiple" multiple="multiple" data-placeholder="Choose ...">
                                                            <optgroup label="What services of Bathroom remodeling you do?">
                                                                @if( !empty($campain_status['campaign_bathroomtype']) )
                                                                    @foreach($campain_status['campaign_bathroomtype'] as $item)
                                                                        @if( empty( $campain_status_selected['campaign_bathroomtype_id_arr'] ) )
                                                                            <option value="{{ $item->campaign_bathroomtype_id }}">{{ $item->campaign_bathroomtype_type }}</option>
                                                                        @else
                                                                            @if( in_array( $item->campaign_bathroomtype_id, $campain_status_selected['campaign_bathroomtype_id_arr']) )
                                                                                <option value="{{ $item->campaign_bathroomtype_id }}" selected>{{ $item->campaign_bathroomtype_type }}</option>
                                                                            @else
                                                                                <option value="{{ $item->campaign_bathroomtype_id }}">{{ $item->campaign_bathroomtype_type }}</option>
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif( $compaign->service_campaign_id == 10 )
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="stairlift_type">Type of stairs  <span class="requiredFields">*</span></label>
                                                        <select id="stairlift_type" disabled name="stairlift_type[]" class="select2 form-control select2-multiple" multiple="multiple" data-placeholder="Choose ...">
                                                            <optgroup label="Type of stairs  ">
                                                                @if( !empty($campain_status['stairs_type_lead']) )
                                                                    @foreach($campain_status['stairs_type_lead'] as $item)
                                                                        @if( empty( $campain_status_selected['campaign_stairs_type_arr'] ) )
                                                                            <option value="{{ $item->stairs_type_lead_id }}">{{ $item->stairs_type_lead_type }}</option>
                                                                        @else
                                                                            @if( in_array( $item->stairs_type_lead_id, $campain_status_selected['campaign_stairs_type_arr']) )
                                                                                <option value="{{ $item->stairs_type_lead_id }}" selected>{{ $item->stairs_type_lead_type }}</option>
                                                                            @else
                                                                                <option value="{{ $item->stairs_type_lead_id }}">{{ $item->stairs_type_lead_type }}</option>
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="stairlift_reason">Stairlift specification <span class="requiredFields">*</span></label>
                                                        <select id="stairlift_reason" disabled name="stairlift_reason[]" class="select2 form-control select2-multiple" multiple="multiple" data-placeholder="Choose ...">
                                                            <optgroup label="Stairlift specification">
                                                                @if( !empty($campain_status['stairs_reason_lead']) )
                                                                    @foreach($campain_status['stairs_reason_lead'] as $item)
                                                                        @if( empty( $campain_status_selected['campaign_stairs_reason_arr'] ) )
                                                                            <option value="{{ $item->stairs_reason_lead_id }}">{{ $item->stairs_reason_lead_type }}</option>
                                                                        @else
                                                                            @if( in_array( $item->stairs_reason_lead_id, $campain_status_selected['campaign_stairs_reason_arr']) )
                                                                                <option value="{{ $item->stairs_reason_lead_id }}" selected>{{ $item->stairs_reason_lead_type }}</option>
                                                                            @else
                                                                                <option value="{{ $item->stairs_reason_lead_id }}">{{ $item->stairs_reason_lead_type }}</option>
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif( $compaign->service_campaign_id == 11 )
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label for="furnance_type">Type of Furnance? <span class="requiredFields">*</span></label>
                                                        <select id="furnance_type" disabled name="furnance_type[]" class="select2 form-control select2-multiple" multiple="multiple" data-placeholder="Choose ...">
                                                            <optgroup label="Type of Furnance? ">
                                                                @if( !empty($campain_status['furnance_type_lead']) )
                                                                    @foreach($campain_status['furnance_type_lead'] as $item)
                                                                        @if( empty( $campain_status_selected['campaign_furnance_type_arr'] ) )
                                                                            <option value="{{ $item->furnance_type_lead_id }}">{{ $item->furnance_type_lead_type }}</option>
                                                                        @else
                                                                            @if( in_array( $item->furnance_type_lead_id, $campain_status_selected['campaign_furnance_type_arr']) )
                                                                                <option value="{{ $item->furnance_type_lead_id }}" selected>{{ $item->furnance_type_lead_type }}</option>
                                                                            @else
                                                                                <option value="{{ $item->furnance_type_lead_id }}">{{ $item->furnance_type_lead_type }}</option>
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif( $compaign->service_campaign_id == 12 )
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label for="Boiler_type">Type of Boiler? <span class="requiredFields">*</span></label>
                                                        <select id="Boiler_type" disabled name="furnance_type[]" class="select2 form-control select2-multiple" multiple="multiple" data-placeholder="Choose ...">
                                                            <optgroup label="Type of Boiler? ">
                                                                @if( !empty($campain_status['furnance_type_lead']) )
                                                                    @foreach($campain_status['furnance_type_lead'] as $item)
                                                                        @if( empty( $campain_status_selected['campaign_furnance_type_arr'] ) )
                                                                            <option value="{{ $item->furnance_type_lead_id }}">{{ $item->furnance_type_lead_type }}</option>
                                                                        @else
                                                                            @if( in_array( $item->furnance_type_lead_id, $campain_status_selected['campaign_furnance_type_arr']) )
                                                                                <option value="{{ $item->furnance_type_lead_id }}" selected>{{ $item->furnance_type_lead_type }}</option>
                                                                            @else
                                                                                <option value="{{ $item->furnance_type_lead_id }}">{{ $item->furnance_type_lead_type }}</option>
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif( $compaign->service_campaign_id == 15 )
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label for="plumbing_service">Type of Plumbing <span class="requiredFields">*</span></label>
                                                        <select id="plumbing_service" disabled name="plumbing_service[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                                                            <optgroup label="Type of Plumbing">
                                                                @if( !empty($campain_status['plumbing_service_list']) )
                                                                    @foreach($campain_status['plumbing_service_list'] as $item)
                                                                        @if( empty( $campain_status_selected['campaign_plumbing_service_arr'] ) )
                                                                            <option value="{{ $item->plumbing_service_list_id }}">{{ $item->plumbing_service_list_type }}</option>
                                                                        @else
                                                                            @if( in_array( $item->plumbing_service_list_id, $campain_status_selected['campaign_plumbing_service_arr']) )
                                                                                <option value="{{ $item->plumbing_service_list_id }}" selected>{{ $item->plumbing_service_list_type }}</option>
                                                                            @else
                                                                                <option value="{{ $item->plumbing_service_list_id }}">{{ $item->plumbing_service_list_type }}</option>
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif( $compaign->service_campaign_id == 17 )
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label for="sunroom_service">What kind of services you do? <span class="requiredFields">*</span></label>
                                                        <select id="sunroom_service" disabled name="sunroom_service[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                                                            <optgroup label="What kind of services you do?">
                                                                @if( !empty($campain_status['sunroom_service_lead']) )
                                                                    @foreach($campain_status['sunroom_service_lead'] as $item)
                                                                        @if( empty( $campain_status_selected['campaign_sunroom_service_arr'] ) )
                                                                            <option value="{{ $item->sunroom_service_lead_id }}">{{ $item->sunroom_service_lead_type }}</option>
                                                                        @else
                                                                            @if( in_array( $item->sunroom_service_lead_id, $campain_status_selected['campaign_sunroom_service_arr']) )
                                                                                <option value="{{ $item->sunroom_service_lead_id }}" selected>{{ $item->sunroom_service_lead_type }}</option>
                                                                            @else
                                                                                <option value="{{ $item->sunroom_service_lead_id }}">{{ $item->sunroom_service_lead_type }}</option>
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif( $compaign->service_campaign_id == 18 )
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label for="handyman_ammount">What kind of services you do? <span class="requiredFields">*</span></label>
                                                        <select id="handyman_ammount" disabled name="handyman_ammount[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                                                            <optgroup label="What kind of services you do?">
                                                                @if( !empty($campain_status['handyman_ammount_work']) )
                                                                    @foreach($campain_status['handyman_ammount_work'] as $item)
                                                                        @if( empty( $campain_status_selected['campaign_handyman_ammountwork_arr'] ) )
                                                                            <option value="{{ $item->handyman_ammount_work_id }}">{{ $item->handyman_ammount_work_type }}</option>
                                                                        @else
                                                                            @if( in_array( $item->handyman_ammount_work_id, $campain_status_selected['campaign_handyman_ammountwork_arr']) )
                                                                                <option value="{{ $item->handyman_ammount_work_id }}" selected>{{ $item->handyman_ammount_work_type }}</option>
                                                                            @else
                                                                                <option value="{{ $item->handyman_ammount_work_id }}">{{ $item->handyman_ammount_work_type }}</option>
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif( $compaign->service_campaign_id == 19 )
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label for="countertops_service">Type of countertop material you work with <span class="requiredFields">*</span></label>
                                                        <select id="countertops_service" disabled name="countertops_service[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                                                            <optgroup label="Type of countertop material you work with">
                                                                @if( !empty($campain_status['countertops_service_lead']) )
                                                                    @foreach($campain_status['countertops_service_lead'] as $item)
                                                                        @if( empty( $campain_status_selected['campaign_countertops_service_arr'] ) )
                                                                            <option value="{{ $item->countertops_service_lead_id }}">{{ $item->countertops_service_lead_type }}</option>
                                                                        @else
                                                                            @if( in_array( $item->countertops_service_lead_id, $campain_status_selected['campaign_countertops_service_arr']) )
                                                                                <option value="{{ $item->countertops_service_lead_id }}" selected>{{ $item->countertops_service_lead_type }}</option>
                                                                            @else
                                                                                <option value="{{ $item->countertops_service_lead_id }}">{{ $item->countertops_service_lead_type }}</option>
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif( $compaign->service_campaign_id == 20 )
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="door_typeproject">Exterior doors/Interior doors <span class="requiredFields">*</span></label>
                                                        <select id="door_typeproject" disabled name="door_typeproject[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                                                            <optgroup label="Exterior doors/Interior doors">
                                                                @if( !empty($campain_status['door_typeproject_lead']) )
                                                                    @foreach($campain_status['door_typeproject_lead'] as $item)
                                                                        @if( empty( $campain_status_selected['campaign_door_typeproject_arr'] ) )
                                                                            <option value="{{ $item->door_typeproject_lead_id }}">{{ $item->door_typeproject_lead_type }}</option>
                                                                        @else
                                                                            @if( in_array( $item->door_typeproject_lead_id, $campain_status_selected['campaign_door_typeproject_arr']) )
                                                                                <option value="{{ $item->door_typeproject_lead_id }}" selected>{{ $item->door_typeproject_lead_type }}</option>
                                                                            @else
                                                                                <option value="{{ $item->door_typeproject_lead_id }}">{{ $item->door_typeproject_lead_type }}</option>
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="number_of_door">Number of doors <span class="requiredFields">*</span></label>
                                                        <select id="number_of_door" disabled name="number_of_door[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                                                            <optgroup label="Number of doors">
                                                                @if( !empty($campain_status['number_of_door_lead']) )
                                                                    @foreach($campain_status['number_of_door_lead'] as $item)
                                                                        @if( empty( $campain_status_selected['campaign_numberof_door_arr'] ) )
                                                                            <option value="{{ $item->number_of_door_lead_id }}">{{ $item->number_of_door_lead_type }}</option>
                                                                        @else
                                                                            @if( in_array( $item->number_of_door_lead_id, $campain_status_selected['campaign_numberof_door_arr']) )
                                                                                <option value="{{ $item->number_of_door_lead_id }}" selected>{{ $item->number_of_door_lead_type }}</option>
                                                                            @else
                                                                                <option value="{{ $item->number_of_door_lead_id }}">{{ $item->number_of_door_lead_type }}</option>
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif( $compaign->service_campaign_id == 21 )
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label for="gutters_meterial">Type of Gutter material you work with <span class="requiredFields">*</span></label>
                                                        <select id="gutters_meterial" disabled name="gutters_meterial[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                                                            <optgroup label="Type of Gutter material you work with">
                                                                @if( !empty($campain_status['gutters_meterial_lead']) )
                                                                    @foreach($campain_status['gutters_meterial_lead'] as $item)
                                                                        @if( empty( $campain_status_selected['campaign_gutters_meterial_arr'] ) )
                                                                            <option value="{{ $item->gutters_meterial_lead_id }}">{{ $item->gutters_meterial_lead_type }}</option>
                                                                        @else
                                                                            @if( in_array( $item->gutters_meterial_lead_id, $campain_status_selected['campaign_gutters_meterial_arr']) )
                                                                                <option value="{{ $item->gutters_meterial_lead_id }}" selected>{{ $item->gutters_meterial_lead_type }}</option>
                                                                            @else
                                                                                <option value="{{ $item->gutters_meterial_lead_id }}">{{ $item->gutters_meterial_lead_type }}</option>
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif( $compaign->service_campaign_id == 22 )
                                            <div id="paving_service_div_section">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h6>What kind of services you do? <span class="requiredFields">*</span></h6>
                                                        @if( !empty($campain_status['paving_service_lead']) )
                                                            @foreach($campain_status['paving_service_lead'] as $item)
                                                                <label class="container-services">{{ $item->paving_service_lead_type }}
                                                                    <input disabled type="checkbox" onchange='multiServicesCheckboxPaving("ps{{ $item->paving_service_lead_id }}")'
                                                                           name="paving_service[]" value="{{ $item->paving_service_lead_id }}"
                                                                           @if(!empty($campain_status_selected['campaign_paving_service_arr'])) @if( in_array( $item->paving_service_lead_id, $campain_status_selected['campaign_paving_service_arr']) ) checked @endif @endif>
                                                                    <span class="checkmark-services"></span>
                                                                </label>
                                                            @endforeach
                                                        @endif

                                                        <div id="ps1" class="service-block"
                                                             @if(!empty($campain_status_selected['campaign_paving_service_arr'])) @if( !(in_array( 1, $campain_status_selected['campaign_paving_service_arr'])) ) style="display:none;" @endif @else style="display:none;" @endif>
                                                            <h6>Asphalt Paving - Install</h6>
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <div class="form-group">
                                                                        <label for="paving_asphalt_type">Areas that you can make the work in? <span class="requiredFields">*</span></label>
                                                                        <select disabled id="paving_asphalt_type" name="paving_asphalt_type[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                                                                            <optgroup label="Areas that you can make the work in?">
                                                                                @if( !empty($campain_status['paving_asphalt_type']) )
                                                                                    @foreach($campain_status['paving_asphalt_type'] as $item)
                                                                                        @if( empty( $campain_status_selected['campaign_paving_asphalt_arr'] ) )
                                                                                            <option value="{{ $item->paving_asphalt_type_id }}">{{ $item->paving_asphalt_type }}</option>
                                                                                        @else
                                                                                            @if( in_array( $item->paving_asphalt_type_id, $campain_status_selected['campaign_paving_asphalt_arr']) )
                                                                                                <option value="{{ $item->paving_asphalt_type_id }}" selected>{{ $item->paving_asphalt_type }}</option>
                                                                                            @else
                                                                                                <option value="{{ $item->paving_asphalt_type_id }}">{{ $item->paving_asphalt_type }}</option>
                                                                                            @endif
                                                                                        @endif
                                                                                    @endforeach
                                                                                @endif
                                                                            </optgroup>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div id="ps3" class="service-block"
                                                             @if(!empty($campain_status_selected['campaign_paving_service_arr'])) @if( !(in_array( 3, $campain_status_selected['campaign_paving_service_arr'])) ) style="display:none;" @endif @else style="display:none;" @endif>
                                                            <h6>Gravel or Loose Fill Paving - Install, Spread or Scrape</h6>
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <div class="form-group">
                                                                        <label for="paving_loose_fill_type">Type of loose fill do you work with<span class="requiredFields">*</span></label>
                                                                        <select disabled id="paving_loose_fill_type" name="paving_loose_fill_type[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                                                                            <optgroup label="Type of loose fill do you work with">
                                                                                @if( !empty($campain_status['paving_loose_fill_type']) )
                                                                                    @foreach($campain_status['paving_loose_fill_type'] as $item)
                                                                                        @if( empty( $campain_status_selected['campaign_paving_loose_fill_arr'] ) )
                                                                                            <option value="{{ $item->paving_loose_fill_type_id }}">{{ $item->paving_loose_fill_type }}</option>
                                                                                        @else
                                                                                            @if( in_array( $item->paving_loose_fill_type_id, $campain_status_selected['campaign_paving_loose_fill_arr']) )
                                                                                                <option value="{{ $item->paving_loose_fill_type_id }}" selected>{{ $item->paving_loose_fill_type }}</option>
                                                                                            @else
                                                                                                <option value="{{ $item->paving_loose_fill_type_id }}">{{ $item->paving_loose_fill_type }}</option>
                                                                                            @endif
                                                                                        @endif
                                                                                    @endforeach
                                                                                @endif
                                                                            </optgroup>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="form-group">
                                                                    <label for="paving_best_describes_priject">Type of project you wish to work with<span class="requiredFields">*</span></label>
                                                                    <select disabled id="paving_best_describes_priject" name="paving_best_describes_priject[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                                                                        <optgroup label="Type of project you wish to work with">
                                                                            @if( !empty($campain_status['paving_best_describes_priject']) )
                                                                                @foreach($campain_status['paving_best_describes_priject'] as $item)
                                                                                    @if( empty( $campain_status_selected['campaign_paving_best_desc_arr'] ) )
                                                                                        <option value="{{ $item->paving_best_describes_priject_id }}">{{ $item->paving_best_describes_priject_type }}</option>
                                                                                    @else
                                                                                        @if( in_array( $item->paving_best_describes_priject_id, $campain_status_selected['campaign_paving_best_desc_arr']) )
                                                                                            <option value="{{ $item->paving_best_describes_priject_id }}" selected>{{ $item->paving_best_describes_priject_type }}</option>
                                                                                        @else
                                                                                            <option value="{{ $item->paving_best_describes_priject_id }}">{{ $item->paving_best_describes_priject_type }}</option>
                                                                                        @endif
                                                                                    @endif
                                                                                @endforeach
                                                                            @endif
                                                                        </optgroup>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif( $compaign->service_campaign_id == 23 )
                                            <div id="painting_service_div_section">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h6>What kind of services you do? <span class="requiredFields">*</span></h6>
                                                        @if( !empty($campain_status['painting_service_lead']) )
                                                            @foreach($campain_status['painting_service_lead'] as $item)
                                                                <label class="container-services">{{ $item->painting_service_lead_type }}
                                                                    <input disabled type="checkbox" onchange='multiServicesCheckboxPainting("painting_service{{ $item->painting_service_lead_id }}")'
                                                                           name="painting_service[]" value="{{ $item->painting_service_lead_id }}" class="painting_service"
                                                                           @if(!empty($campain_status_selected['campaign_painting_service_arr'])) @if( in_array( $item->painting_service_lead_id, $campain_status_selected['campaign_painting_service_arr']) ) checked @endif @endif>
                                                                    <span class="checkmark-services"></span>
                                                                </label>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>

                                                <div id="painting_service1" class="service-block"
                                                     @if(!empty($campain_status_selected['campaign_painting_service_arr'])) @if( !(in_array( 1, $campain_status_selected['campaign_painting_service_arr'])) ) style="display:none;" @endif @else style="display:none;" @endif>
                                                    <h6>Exterior Home or Structure - Paint or Stain</h6>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="painting1_typeof_project">Type of project<span class="requiredFields">*</span></label>
                                                                <select disabled id="painting1_typeof_project" name="painting1_typeof_project[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                                                                    <optgroup label="Type of project">
                                                                        @if( !empty($campain_status['painting1_typeof_project']) )
                                                                            @foreach($campain_status['painting1_typeof_project'] as $item)
                                                                                @if( empty( $campain_status_selected['campaign_painting1_projecttyp_arr'] ) )
                                                                                    <option value="{{ $item->painting1_typeof_project_id }}">{{ $item->painting1_typeof_project_type }}</option>
                                                                                @else
                                                                                    @if( in_array( $item->painting1_typeof_project_id, $campain_status_selected['campaign_painting1_projecttyp_arr']) )
                                                                                        <option value="{{ $item->painting1_typeof_project_id }}" selected>{{ $item->painting1_typeof_project_type }}</option>
                                                                                    @else
                                                                                        <option value="{{ $item->painting1_typeof_project_id }}">{{ $item->painting1_typeof_project_type }}</option>
                                                                                    @endif
                                                                                @endif
                                                                            @endforeach
                                                                        @endif
                                                                    </optgroup>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="painting1_kindsof_surfaces">Areas that you can paint and/or staine?<span class="requiredFields">*</span></label>
                                                                <select disabled id="painting1_kindsof_surfaces" name="painting1_kindsof_surfaces[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                                                                    <optgroup label="Areas that you can paint and/or staine?">
                                                                        @if( !empty($campain_status['painting1_kindsof_surfaces']) )
                                                                            @foreach($campain_status['painting1_kindsof_surfaces'] as $item)
                                                                                @if( empty( $campain_status_selected['campaign_p1_kindsof_surfaces_arr'] ) )
                                                                                    <option value="{{ $item->painting1_kindsof_surfaces_id }}">{{ $item->painting1_kindsof_surfaces_type }}</option>
                                                                                @else
                                                                                    @if( in_array( $item->painting1_kindsof_surfaces_id, $campain_status_selected['campaign_p1_kindsof_surfaces_arr']) )
                                                                                        <option value="{{ $item->painting1_kindsof_surfaces_id }}" selected>{{ $item->painting1_kindsof_surfaces_type }}</option>
                                                                                    @else
                                                                                        <option value="{{ $item->painting1_kindsof_surfaces_id }}">{{ $item->painting1_kindsof_surfaces_type }}</option>
                                                                                    @endif
                                                                                @endif
                                                                            @endforeach
                                                                        @endif
                                                                    </optgroup>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div id="painting_service2" class="service-block"
                                                     @if(!empty($campain_status_selected['campaign_painting_service_arr'])) @if( !(in_array( 2, $campain_status_selected['campaign_painting_service_arr'])) ) style="display:none;" @endif @else style="display:none;" @endif>
                                                    <h6>Interior Home or Surfaces - Paint or Stain</h6>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="painting2_rooms_number">Number of Rooms<span class="requiredFields">*</span></label>
                                                                <select disabled id="painting2_rooms_number" name="painting2_rooms_number[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                                                                    <optgroup label="Number of Rooms">
                                                                        @if( !empty($campain_status['painting2_rooms_number']) )
                                                                            @foreach($campain_status['painting2_rooms_number'] as $item)
                                                                                @if( empty( $campain_status_selected['campaign_p2_rooms_number_arr'] ) )
                                                                                    <option value="{{ $item->painting2_rooms_number_id }}">{{ $item->painting2_rooms_number_type }}</option>
                                                                                @else
                                                                                    @if( in_array( $item->painting2_rooms_number_id, $campain_status_selected['campaign_p2_rooms_number_arr']) )
                                                                                        <option value="{{ $item->painting2_rooms_number_id }}" selected>{{ $item->painting2_rooms_number_type }}</option>
                                                                                    @else
                                                                                        <option value="{{ $item->painting2_rooms_number_id }}">{{ $item->painting2_rooms_number_type }}</option>
                                                                                    @endif
                                                                                @endif
                                                                            @endforeach
                                                                        @endif
                                                                    </optgroup>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="painting2_typeof_paint">Areas that you paint?<span class="requiredFields">*</span></label>
                                                                <select disabled id="painting2_typeof_paint" name="painting2_typeof_paint[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                                                                    <optgroup label="Areas that you paint?">
                                                                        @if( !empty($campain_status['painting2_typeof_paint']) )
                                                                            @foreach($campain_status['painting2_typeof_paint'] as $item)
                                                                                @if( empty( $campain_status_selected['campaign_p2_typeof_paint_arr'] ) )
                                                                                    <option value="{{ $item->painting2_typeof_paint_id }}">{{ $item->painting2_typeof_paint_type }}</option>
                                                                                @else
                                                                                    @if( in_array( $item->painting2_typeof_paint_id, $campain_status_selected['campaign_p2_typeof_paint_arr']) )
                                                                                        <option value="{{ $item->painting2_typeof_paint_id }}" selected>{{ $item->painting2_typeof_paint_type }}</option>
                                                                                    @else
                                                                                        <option value="{{ $item->painting2_typeof_paint_id }}">{{ $item->painting2_typeof_paint_type }}</option>
                                                                                    @endif
                                                                                @endif
                                                                            @endforeach
                                                                        @endif
                                                                    </optgroup>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div id="painting_service3" class="service-block"
                                                     @if(!empty($campain_status_selected['campaign_painting_service_arr'])) @if( !(in_array( 3, $campain_status_selected['campaign_painting_service_arr'])) ) style="display:none;" @endif @else style="display:none;" @endif>
                                                    <h6>Painting or Staining - Small Projects</h6>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label for="painting3_each_feature">Areas that you can paint and/or staine?<span class="requiredFields">*</span></label>
                                                                <select disabled id="painting3_each_feature" name="painting3_each_feature[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                                                                    <optgroup label="Areas that you can paint and/or staine?">
                                                                        @if( !empty($campain_status['painting3_each_feature']) )
                                                                            @foreach($campain_status['painting3_each_feature'] as $item)
                                                                                @if( empty( $campain_status_selected['campaign_p3_each_feature_arr'] ) )
                                                                                    <option value="{{ $item->painting3_each_feature_id }}">{{ $item->painting3_each_feature_type }}</option>
                                                                                @else
                                                                                    @if( in_array( $item->painting3_each_feature_id, $campain_status_selected['campaign_p3_each_feature_arr']) )
                                                                                        <option value="{{ $item->painting3_each_feature_id }}" selected>{{ $item->painting3_each_feature_type }}</option>
                                                                                    @else
                                                                                        <option value="{{ $item->painting3_each_feature_id }}">{{ $item->painting3_each_feature_type }}</option>
                                                                                    @endif
                                                                                @endif
                                                                            @endforeach
                                                                        @endif
                                                                    </optgroup>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div id="painting_service4" class="service-block"
                                                     @if(!empty($campain_status_selected['campaign_painting_service_arr'])) @if( !(in_array( 4, $campain_status_selected['campaign_painting_service_arr'])) ) style="display:none;" @endif @else style="display:none;" @endif>
                                                    <h6>Metal Roofing - Paint</h6>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label for="painting4_existing_roof">Type/condition of the roof you wish to work with<span class="requiredFields">*</span></label>
                                                                <select disabled id="painting4_existing_roof" name="painting4_existing_roof[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                                                                    <optgroup label="Type/condition of the roof you wish to work with">
                                                                        @if( !empty($campain_status['painting4_existing_roof']) )
                                                                            @foreach($campain_status['painting4_existing_roof'] as $item)
                                                                                @if( empty( $campain_status_selected['campaign_p4_existing_roof_arr'] ) )
                                                                                    <option value="{{ $item->painting4_existing_roof_id }}">{{ $item->painting4_existing_roof_type }}</option>
                                                                                @else
                                                                                    @if( in_array( $item->painting4_existing_roof_id, $campain_status_selected['campaign_p4_existing_roof_arr']) )
                                                                                        <option value="{{ $item->painting4_existing_roof_id }}" selected>{{ $item->painting4_existing_roof_type }}</option>
                                                                                    @else
                                                                                        <option value="{{ $item->painting4_existing_roof_id }}">{{ $item->painting4_existing_roof_type }}</option>
                                                                                    @endif
                                                                                @endif
                                                                            @endforeach
                                                                        @endif
                                                                    </optgroup>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div id="painting_service5" class="service-block"
                                                     @if(!empty($campain_status_selected['campaign_painting_service_arr'])) @if( !(in_array( 5, $campain_status_selected['campaign_painting_service_arr'])) ) style="display:none;" @endif @else style="display:none;" @endif>
                                                    <h6>Metal Roofing - Paint</h6>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="painting5_kindof_texturing">Type of project<span class="requiredFields">*</span></label>
                                                                <select disabled id="painting5_kindof_texturing" name="painting5_kindof_texturing[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                                                                    <optgroup label="Type of project">
                                                                        @if( !empty($campain_status['painting5_kindof_texturing']) )
                                                                            @foreach($campain_status['painting5_kindof_texturing'] as $item)
                                                                                @if( empty( $campain_status_selected['campaign_p5_kindof_texturing_arr'] ) )
                                                                                    <option value="{{ $item->painting5_kindof_texturing_id }}">{{ $item->painting5_kindof_texturing_type }}</option>
                                                                                @else
                                                                                    @if( in_array( $item->painting5_kindof_texturing_id, $campain_status_selected['campaign_p5_kindof_texturing_arr']) )
                                                                                        <option value="{{ $item->painting5_kindof_texturing_id }}" selected>{{ $item->painting5_kindof_texturing_type }}</option>
                                                                                    @else
                                                                                        <option value="{{ $item->painting5_kindof_texturing_id }}">{{ $item->painting5_kindof_texturing_type }}</option>
                                                                                    @endif
                                                                                @endif
                                                                            @endforeach
                                                                        @endif
                                                                    </optgroup>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="painting5_surfaces_textured">Areas that you surface<span class="requiredFields">*</span></label>
                                                                <select disabled id="painting5_surfaces_textured" name="painting5_surfaces_textured[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                                                                    <optgroup label="Areas that you surface">
                                                                        @if( !empty($campain_status['painting5_surfaces_textured']) )
                                                                            @foreach($campain_status['painting5_surfaces_textured'] as $item)
                                                                                @if( empty( $campain_status_selected['campaign_p5_surfaces_textured_arr'] ) )
                                                                                    <option value="{{ $item->painting5_surfaces_textured_id }}">{{ $item->painting5_surfaces_textured_type }}</option>
                                                                                @else
                                                                                    @if( in_array( $item->painting5_surfaces_textured_id, $campain_status_selected['campaign_p5_surfaces_textured_arr']) )
                                                                                        <option value="{{ $item->painting5_surfaces_textured_id }}" selected>{{ $item->painting5_surfaces_textured_type }}</option>
                                                                                    @else
                                                                                        <option value="{{ $item->painting5_surfaces_textured_id }}">{{ $item->painting5_surfaces_textured_type }}</option>
                                                                                    @endif
                                                                                @endif
                                                                            @endforeach
                                                                        @endif
                                                                    </optgroup>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="painting1_stories_number">Number of stories of the building you wish to work with<span class="requiredFields">*</span></label>
                                                            <select disabled id="painting1_stories_number" name="painting1_stories_number[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                                                                <optgroup label="Number of stories of the building you wish to work with">
                                                                    @if( !empty($campain_status['painting1_stories_number']) )
                                                                        @foreach($campain_status['painting1_stories_number'] as $item)
                                                                            @if( empty( $campain_status_selected['campaign_p_stories_number_arr'] ) )
                                                                                <option value="{{ $item->painting1_stories_number_id }}">{{ $item->painting1_stories_number_type }}</option>
                                                                            @else
                                                                                @if( in_array( $item->painting1_stories_number_id, $campain_status_selected['campaign_p_stories_number_arr']) )
                                                                                    <option value="{{ $item->painting1_stories_number_id }}" selected>{{ $item->painting1_stories_number_type }}</option>
                                                                                @else
                                                                                    <option value="{{ $item->painting1_stories_number_id }}">{{ $item->painting1_stories_number_type }}</option>
                                                                                @endif
                                                                            @endif
                                                                        @endforeach
                                                                    @endif
                                                                </optgroup>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="historical_structure">Do you wish to receive historical structure painting project?<span class="requiredFields">*</span></label>
                                                            <select disabled id="historical_structure" name="historical_structure[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                                                                <optgroup label="Do you wish to receive historical structure painting project?">
                                                                    @if( empty( $campain_status_selected['campaign_p_historical_structure_arr'] ) )
                                                                        <option value="1">Yes</option>
                                                                        <option value="2">No</option>
                                                                    @else
                                                                        <option value="1" <?php if( in_array('1', $campain_status_selected['campaign_p_historical_structure_arr'] ) ) { echo "selected"; } ?> >Yes</option>
                                                                        <option value="2" <?php if( in_array('2', $campain_status_selected['campaign_p_historical_structure_arr'] ) ) { echo "selected"; } ?> >No</option>
                                                                    @endif
                                                                </optgroup>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="budget_bid_shared">Budget Bid Shared</label>
                                                    <input type="number" class="form-control" id="budget_bid_shared" name="budget_bid_shared" placeholder="" readonly required=""
                                                           @if($compaign->campaign_budget_bid_shared) value="{{ $compaign->campaign_budget_bid_shared }}" @else value="0" @endif>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="budget_bid_exclusive">Budget Bid Exclusive</label>
                                                    <input type="number" class="form-control" id="budget_bid_exclusive" name="budget_bid_exclusive" placeholder="" readonly required=""
                                                           @if($compaign->campaign_budget_bid_exclusive) value="{{ $compaign->campaign_budget_bid_exclusive }}" @else value="0" @endif>
                                                </div>
                                            </div>
                                        </div>

                                        {{--Auto Pay--}}
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="budget_bid_shared" style="display: block;">Auto Pay Campaign</label>
                                                    <label class="form-group switch">
                                                        <input type="checkbox" name="auto_pay_status" disabled id="auto_pay_status" value="1"
                                                        <?php
                                                            if($compaign->auto_pay_status !== null){
                                                                if($compaign->auto_pay_status == 1){
                                                                    echo "checked";
                                                                }
                                                            }
                                                            ?>>
                                                        <span class="slider round"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="budget_bid_exclusive">Auto Pay Amount</label>
                                                    <input type="number" class="form-control" id="auto_pay_amount" disabled name="auto_pay_amount" placeholder=""
                                                           @if($compaign->auto_pay_amount) value="{{ $compaign->auto_pay_amount }}" @else value="0" @endif">
                                                </div>
                                            </div>
                                        </div>
                                        {{--End Auto Pay--}}

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Of Page 1-->
@endsection

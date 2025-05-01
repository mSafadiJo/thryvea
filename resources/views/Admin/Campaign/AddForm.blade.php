@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Add Campaign Pay Per Lead</h4>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="wrapper-page wrapper-page-camp">
            <div class="account-pages AlliedAccount-PagesAdmin">
                <div class="account-box" style="max-width: unset;">
                    <div class="account-content">

                        <!-- Basic Form Wizard -->
                        <div class="row">
                            <div class="col-md-12">
                                <form id="default-wizard" action="{{ route('AdminCampaignStor') }}" method="POST" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <fieldset title="1">
                                        <legend>Campaign Details</legend>

                                        <div class="row m-t-20">
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="Campaign_name">Campaign Name<span class="requiredFields">*</span></label>
                                                            <input type="text" class="form-control" id="Campaign_name" name="Campaign_name" placeholder="" required=""
                                                                   value="{{ old('Campaign_name') }}">
                                                            <input type="hidden" name="type_id" required=""
                                                                   value="{{ $type_id }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="Lead Source">Lead Source<span class="requiredFields">*</span></label>
                                                            <select id="LeadSourceList" multiple="multiple" class="select2 form-control select2-multiple LeadSource" name="LeadSource[]" required data-placeholder="Choose ...">
                                                                <optgroup label="Lead Source">
                                                                    <option value="All Source" selected>All Source</option>
                                                                    @foreach( $platforms as $val )
                                                                        <option value="{{ $val->name }}">{{ $val->name }}</option>
                                                                    @endforeach
                                                                </optgroup>
                                                            </select>
                                                            <input type="button" class="select_all select_all_select" data-classes="LeadSource" value="Select All">
                                                            <input type="button" class="clear_all_state clear_all_select" data-classes="LeadSource" value="Clear All">
                                                        </div>
                                                    </div>

                                                    <div class="@if($type_id == "6") col-sm-4 @else col-sm-6 @endif">
                                                        <div class="form-group">
                                                            <label for="service_id">Service Name<span class="requiredFields">*</span></label>
                                                            <select class="select2 form-control" name="service_id" id="service_idCampainAddAdmnin" required="">
                                                                <optgroup label="Service Name">
                                                                    @if( !empty( $services ) )
                                                                        @foreach( $services as $service )
                                                                            <option value="{{ $service->service_campaign_id }}">{{ $service->service_campaign_name }}</option>
                                                                        @endforeach
                                                                    @endif
                                                                </optgroup>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="@if($type_id == "6") col-sm-4 @else col-sm-6 @endif">
                                                        <div class="form-group">
                                                            <label for="Buyers_id">Buyers<span class="requiredFields">*</span></label>
                                                            <select class="select2 form-control" name="Buyers_id" id="Buyers_id" required="">
                                                                <optgroup label="Buyers Name">
                                                                    @if( !empty( $users ) )
                                                                        @foreach( $users as $user )
                                                                            @if( $buyer_id == $user->id )
                                                                                <option value="{{ $user->id }}" selected>{{ $user->user_business_name }}</option>
                                                                            @else
                                                                                <option value="{{ $user->id }}">{{ $user->user_business_name }}</option>
                                                                            @endif
                                                                        @endforeach
                                                                    @endif
                                                                </optgroup>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    @if($type_id == "6")
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label for="transfer_numbers">Transfer Numbers<span class="requiredFields">*</span></label>
                                                                <input type="text" class="form-control" id="transfer_numbers" name="transfer_numbers" placeholder="" value="{{ old('transfer_numbers') }}">
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <div class="col-sm-12">
                                                        <h6>Choose as Shared Budget Details</h6>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="budget">Budget</label>
                                                            <input type="number" class="form-control" id="budget" name="budget" placeholder=""
                                                                   value="{{ old('budget') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="budget_period">Period</label>
                                                            <select id="budget_period" name="budget_period" class="form-control">
                                                                <option value="1" @if( old('budget_period') == 1) selected @endif >Daily</option>
                                                                <option value="2" @if( old('budget_period') == 2) selected @endif >Weekly</option>
                                                                <option value="3" @if( old('budget_period') == 3) selected @endif >Monthly</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="numberOfCustomerCampaign">Lead Capacity</label>
                                                            <input type="number" class="form-control" id="numberOfCustomerCampaign" name="numberOfCustomerCampaign" placeholder=""
                                                                   value="{{ old('numberOfCustomerCampaign') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="numberOfCustomerCampaign_period">Period</label>
                                                            <select id="numberOfCustomerCampaign_period" name="numberOfCustomerCampaign_period" class="form-control">
                                                                <option value="1" @if( old('numberOfCustomerCampaign_period') == 1) selected @endif >Daily</option>
                                                                <option value="2" @if( old('numberOfCustomerCampaign_period') == 2) selected @endif >Weekly</option>
                                                                <option value="3" @if( old('numberOfCustomerCampaign_period') == 3) selected @endif >Monthly</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-12">
                                                        <h6>Choose as Exclusive Budget Details</h6>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="budget_exclusive">Budget</label>
                                                            <input type="number" class="form-control" id="budget_exclusive" name="budget_exclusive" placeholder=""
                                                                   value="{{ old('budget_exclusive') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="budget_period_exclusive">Period</label>
                                                            <select id="budget_period_exclusive" name="budget_period_exclusive" class="form-control">
                                                                <option value="1" @if( old('budget_period_exclusive') == 1) selected @endif >Daily</option>
                                                                <option value="2" @if( old('budget_period_exclusive') == 2) selected @endif >Weekly</option>
                                                                <option value="3" @if( old('budget_period_exclusive') == 3) selected @endif >Monthly</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="numberOfCustomerCampaign_exclusive">Lead Capacity</label>
                                                            <input type="number" class="form-control" id="numberOfCustomerCampaign_exclusive" name="numberOfCustomerCampaign_exclusive" placeholder=""
                                                                   value="{{ old('numberOfCustomerCampaign_exclusive') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="numberOfCustomerCampaign_period_exclusive">Period</label>
                                                            <select id="numberOfCustomerCampaign_period_exclusive" name="numberOfCustomerCampaign_period_exclusive" class="form-control">
                                                                <option value="1" @if( old('numberOfCustomerCampaign_period_exclusive') == 1) selected @endif >Daily</option>
                                                                <option value="2" @if( old('numberOfCustomerCampaign_period_exclusive') == 2) selected @endif >Weekly</option>
                                                                <option value="3" @if( old('numberOfCustomerCampaign_period_exclusive') == 3) selected @endif >Monthly</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                    <fieldset title="2">
                                        <legend>Campaign Target</legend>

                                        <div class="row m-t-20">
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <h6>Choose a place among my working areas</h6>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group stateCampName">
                                                            <label for="state">State</label>
                                                            <select class="select2 form-control select2-multiple stateCam" name="state[]" id="stateCampName" multiple="multiple" data-placeholder="Choose ...">
                                                                <optgroup label="States">
                                                                    @if(!empty($address['states']))
                                                                        @foreach($address['states'] as $state)
                                                                            @if(empty(old('state')))
                                                                                <option value="{{ $state->state_id }}">{{ $state->state_code }}</option>
                                                                            @else
                                                                                @if(in_array($state->state_id, old('state')))
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
                                                        <div class="form-group countyFilter">
                                                            <label for="county">County</label>
                                                            <select class="form-control countyCam" name="county[]" id="county" multiple="multiple" >
                                                                <optgroup label="Counties">

                                                                </optgroup>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group city_filter">
                                                            <label for="City_Name">City Name</label>
                                                            <select class="form-control  city_filter" multiple name="city[]" id="City_Name" >
                                                                <optgroup label="">

                                                                </optgroup>
                                                            </select>
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
                                                        <div class="form-group zip_filter">
                                                            <label for="Zip_Codes">Zip Codes</label>
                                                            <select class="form-control zip_filter" multiple name="zipcode[]" id="Zip_Name">
                                                                <optgroup label="">

                                                                </optgroup>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="distance_area">Distance Area</label>
                                                            <input type="number" class="form-control" id="distance_area" name="distance_area" placeholder=""
                                                                   value="{{ old('distance_area') }}">
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <h6>Places that aren't among my working area</h6>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="county_expect">County</label>
                                                            <select class="form-control expectCounty" name="county_expect[]" id="county_expect" multiple="multiple">
                                                                <optgroup label="Counties">

                                                                </optgroup>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group city_filter_expect">
                                                            <label for="City_Name">City Name</label>
                                                            <select class="form-control city_filter" multiple name="city_expect[]" id="City_Name_expect" >
                                                                <optgroup label="">

                                                                </optgroup>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="zipcode">Zip-Code</label>
                                                            <input type="file" class="form-control" name="listOfzipcode_expect" id="listOfzipcode_expect" accept=".xls,.xlsx,.csv">
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>

                                    </fieldset>

                                    <fieldset title="3">
                                        <legend>Time Delivery</legend>

                                        <div class="row m-t-20">
                                            <div class="col-sm-12">
                                                <div class="row timemargin">
                                                    <div class="col-sm-1">
                                                        <label for="state">Timezone</label>
                                                    </div>
                                                    <div class="col-sm-5">
                                                        <select class="select2 form-control" required name="timezone" id="timezone" data-placeholder="Choose ...">
                                                            <optgroup label="Timezone">
                                                                <option value="5" @if( !empty(old('timezone')) ) @if( old('timezone') == 5 ) selected @endif @else selected @endif >Eastern Time</option>
                                                                <option value="6" @if( old('timezone') == 6 ) selected @endif>Central Time</option>
                                                                <option value="7" @if( old('timezone') == 7 ) selected @endif>Mountain Time</option>
                                                                <option value="8" @if( old('timezone') == 8 ) selected @endif>Pacific Time</option>
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
                                                        <input type="text" class="form-control timepicker" id="starttime-Sun" required name="starttime_Sun" placeholder="Start Time" value="{{ old('starttime_Sun') }}">
                                                    </div> <div class="col-sm-2">
                                                        <input type="text" class="form-control timepicker" id="endtime-Sun" required name="endtime_Sun" placeholder="End Time" value="{{ old('endtime_Sun') }}">
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <label class="container-dayoff">Day Off
                                                            <input type="checkbox" id="offday-Sun" name="offday_Sun" value="1"
                                                                   @if( old('offday_Sun') == 1 )
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
                                                        <input type="text" class="form-control timepicker" id="starttime-Mon" required name="starttime_Mon" placeholder="Start Time" value="{{ old('starttime_Mon') }}">
                                                    </div> <div class="col-sm-2">
                                                        <input type="text" class="form-control timepicker" id="endtime-Mon" required name="endtime_Mon" placeholder="End Time" value="{{ old('endtime_Mon') }}">
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <label class="container-dayoff">Day Off
                                                            <input type="checkbox" id="offday-Mon" name="offday_Mon" value="1"
                                                                   @if( old('offday_Mon') == 1)
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
                                                        <input type="text" class="form-control timepicker" id="starttime-Tus" required name="starttime_Tus" placeholder="Start Time" value="{{ old('starttime_Tus') }}">
                                                    </div> <div class="col-sm-2">
                                                        <input type="text" class="form-control timepicker" id="endtime-Tus" required name="endtime_Tus" placeholder="End Time" value="{{ old('endtime_Tus') }}">
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <label class="container-dayoff">Day Off
                                                            <input type="checkbox" id="offday-Tus" name="offday_Tus" value="1"
                                                                   @if( old('offday_Tus') == 1 )
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
                                                        <input type="text" class="form-control timepicker" id="starttime-Wen" required name="starttime_Wen" placeholder="Start Time" value="{{ old('starttime_Wen') }}">
                                                    </div> <div class="col-sm-2">
                                                        <input type="text" class="form-control timepicker" id="endtime-Wen" required name="endtime_Wen" placeholder="End Time" value="{{ old('endtime_Wen') }}">
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <label class="container-dayoff">Day Off
                                                            <input type="checkbox" id="offday-Wen" name="offday_Wen" value="1"
                                                                   @if(old('offday_Wen') == 1 )
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
                                                        <input type="text" class="form-control timepicker" id="starttime-Thr" required name="starttime_Thr" placeholder="Start Time" value="{{ old('starttime_Thr') }}">
                                                    </div> <div class="col-sm-2">
                                                        <input type="text" class="form-control timepicker" id="endtime-Thr" required name="endtime_Thr" placeholder="End Time" value="{{ old('endtime_Thr') }}">
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <label class="container-dayoff">Day Off
                                                            <input type="checkbox" id="offday-Thr" name="offday_Thr" value="1"
                                                                   @if( old('offday_Thr') == 1 )
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
                                                        <input type="text" class="form-control timepicker" id="starttime-fri" required name="starttime_fri" placeholder="Start Time" value="{{ old('starttime_fri') }}">
                                                    </div> <div class="col-sm-2">
                                                        <input type="text" class="form-control timepicker" id="endtime-fri" required name="endtime_fri" placeholder="End Time" value="{{ old('endtime_fri') }}">
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <label class="container-dayoff">Day Off
                                                            <input type="checkbox" id="offday-fri" name="offday_fri" value="1"
                                                                   @if( old('offday_fri') == 1 )
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
                                                        <input type="text" class="form-control timepicker" id="starttime-sat" required name="starttime_sat" placeholder="Start Time" value="{{ old('starttime_sat') }}">
                                                    </div> <div class="col-sm-2">
                                                        <input type="text" class="form-control timepicker" id="endtime-sat" required name="endtime_sat" placeholder="End Time" value="{{ old('endtime_sat') }}">
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <label class="container-dayoff">Day Off
                                                            <input type="checkbox" id="offday-sat" name="offday_sat" value="1"
                                                                   @if( old('offday_sat') == 1 )
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
                                                        <label class="col-md-6 control-label text-center">Availability 24/7</label>
                                                        <label class="col-md-6 switch">
                                                            <input type="checkbox" name="hourin7days" id="24hourin7days" value="1"
                                                            <?php
                                                                if(old('hourin7days') !== null){
                                                                    if(old('hourin7days') == 1){
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

                                    </fieldset>

                                    <fieldset title="4">
                                        {{--Delivery Method--}}
                                        @include('include.Campaigns.add_delivery_methods')
                                        {{--End Delivery Method--}}
                                    </fieldset>

                                    <fieldset title="5">
                                        <legend>Campaign Bid</legend>

                                        <div class="row m-t-20">
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="propertytype">Property Type<span class="requiredFields">*</span></label>
                                                            <select id="propertytype" name="propertytype[]" class="select2 form-control select2-multiple" required="" multiple="multiple" data-placeholder="Choose ...">
                                                                <optgroup label="Property Type">
                                                                    <option value="1" @if(!empty(old('propertytype'))) @if(in_array(1, old('propertytype'))) selected @endif @endif>Owned</option>
                                                                    <option value="2" @if(!empty(old('propertytype'))) @if(in_array(2, old('propertytype'))) selected @endif @endif>Rented</option>
                                                                    <option value="3" @if(!empty(old('propertytype'))) @if(in_array(3, old('propertytype'))) selected @endif @endif>Business</option>
                                                                </optgroup>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="Installings">Type Of Service<span class="requiredFields">*</span></label>
                                                            <select id="Installings" name="Installings[]" class="select2 form-control select2-multiple" required="" multiple="multiple" data-placeholder="Choose ...">
                                                                <optgroup label="Type Of Service">
                                                                    <option value="1" @if(!empty(old('Installings'))) @if(in_array(1, old('Installings'))) selected @endif @endif>Install</option>
                                                                    <option value="2" @if(!empty(old('Installings'))) @if(in_array(2, old('Installings'))) selected @endif @endif>Replace</option>
                                                                    <option value="3" @if(!empty(old('Installings'))) @if(in_array(3, old('Installings'))) selected @endif @endif>Repair</option>
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
                                                                    <option value="1" @if(!empty(old('customPaid'))) @if(in_array(1, old('customPaid'))) selected @endif @endif>Exclusive</option>
                                                                    <option value="2" @if(!empty(old('customPaid'))) @if(in_array(2, old('customPaid'))) selected @endif @endif>Shared</option>
                                                                </optgroup>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="homeowned">Owner<span class="requiredFields">*</span></label>
                                                            <select id="homeowned" name="homeowned[]" class="select2 form-control select2-multiple" required="" multiple="multiple" data-placeholder="Choose ...">
                                                                <optgroup label="Owner">
                                                                    <option value="1" @if(!empty(old('homeowned'))) @if(in_array(1, old('homeowned'))) selected @endif @endif>Yes</option>
                                                                    <option value="0" @if(!empty(old('homeowned'))) @if(in_array(0, old('homeowned'))) selected @endif @endif>No</option>
                                                                </optgroup>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{--questions--}}
                                                @include('include.Campaigns.add_questions')
                                                {{--End questions--}}
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="budget_bid_shared">Budget Bid Shared</label>
                                                            <input type="number" class="form-control" id="budget_bid_shared" name="budget_bid_shared" placeholder=""
                                                                   value="{{ old('budget_bid_shared') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="budget_bid_exclusive">Budget Bid Exclusive</label>
                                                            <input type="number" class="form-control" id="budget_bid_exclusive" name="budget_bid_exclusive" placeholder=""
                                                                   value="{{ old('budget_bid_exclusive') }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label for="auto_pay_status" style="display: block;">do you accept the same lead you bought for another service?</label>
                                                                    <label class="form-group switch">
                                                                        <input type="checkbox" name="multi_service_accept" id="auto_pay_status" value="1"
                                                                        <?php
                                                                            if(old('multi_service_accept') !== null){
                                                                                if(old('multi_service_accept') == 1){
                                                                                    echo "checked";
                                                                                }
                                                                            } else {
                                                                                echo "checked";
                                                                            }
                                                                            ?>>
                                                                        <span class="slider round"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label for="auto_pay_status" style="display: block;">do you accept seconds service?</label>
                                                                    <label class="form-group switch">
                                                                        <input type="checkbox" name="sec_service_accept" id="auto_pay_status" value="1"
                                                                        <?php
                                                                            if(old('sec_service_accept') !== null){
                                                                                if(old('sec_service_accept') == 1){
                                                                                    echo "checked";
                                                                                }
                                                                            } else {
                                                                                echo "checked";
                                                                            }
                                                                            ?>>
                                                                        <span class="slider round"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                    </fieldset>

                                    <button type="submit" class="btn btn-primary stepy-finish" id="submitFormCam">Submit</button>
                                    {{--                                    <button class="buttonloadCam btn btn-primary stepy-finish" disabled style="display: none">--}}
                                    {{--                                        <i class="fa fa-circle-o-notch fa-spin"></i>Loading--}}
                                    {{--                                    </button>--}}
                                </form>
                            </div>
                        </div>

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
                    </div>
                </div>
                <!-- end card-box-->
            </div>


        </div>
        <!-- end wrapper -->
    </div>
    <!-- End Of Page 1-->
@endsection

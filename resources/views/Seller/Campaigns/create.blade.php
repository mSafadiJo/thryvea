@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Add Campaign</h4>
            </div>
        </div>
    </div>

    <div class="col-sm-12">

        <div class="wrapper-page">

            <div class="account-pages">
                <div class="account-box" style="max-width: unset;">
                    <div class="account-content">

                        <!-- Basic Form Wizard -->
                        <div class="row">
                            <div class="col-md-12">
                                <form id="default-wizard" action="{{ route('Campaigns.store') }}" method="POST" enctype="multipart/form-data">
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
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="Lead Source">Lead Source<span class=""></span></label>
                                                            <select id="LeadSourceList" class="select2 form-control" name="typeOFLead_Source" required data-placeholder="Choose ...">
                                                                <optgroup label="Lead Source">
                                                                    @foreach( $platforms as $val )
                                                                        @if( !empty(old('typeOFLead_Source')) )
                                                                            @if(old('typeOFLead_Source') == $val->name)
                                                                                <option value="{{ $val->name }}" selected>{{ $val->name }}</option>
                                                                            @else
                                                                                <option value="{{ $val->name }}">{{ $val->name }}</option>
                                                                            @endif
                                                                        @else
                                                                            @if( $val->name == "Affiliate" )
                                                                                <option value="{{ $val->name }}" selected>{{ $val->name }}</option>
                                                                            @else
                                                                                <option value="{{ $val->name }}">{{ $val->name }}</option>
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                </optgroup>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
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

                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="Buyers_id">Sellers<span class="requiredFields">*</span></label>
                                                            <select class="select2 form-control" name="seller_id" id="seller_id" required="">
                                                                <optgroup label="Sellers Name">
                                                                    @if( !empty( $users ) )
                                                                        @foreach( $users as $user )
                                                                            <option value="{{ $user->id }}">{{ $user->user_business_name }}</option>
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
                                                            <select class="form-control countyCam" name="county[]" id="county" multiple="multiple">
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
                                                            <select class="form-control city_filter" multiple name="city[]" id="City_Name" >
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
                                                            <select class="form-control zip_filter" multiple name="zipcode[]" id="Zip_Name" >
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
                                                            <select class="form-control expectCounty" name="county_expect[]" id="county_expect" multiple="multiple" >
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
                                        <legend>Campaign Bid</legend>

                                        <div class="row m-t-20">
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <div class="col-sm-4">
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
                                                    <div class="col-sm-4">
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
                                                    <div class="col-sm-4">
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
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label for="budget_bid_shared" style="display: block;">Is PING & POST</label>
                                                            <label class="form-group switch">
                                                                <input type="checkbox" name="is_ping_account" id="is_ping_account" value="1"
                                                                <?php
                                                                    if(old('is_ping_account') !== null){
                                                                        if(old('is_ping_account') == 1){
                                                                            echo "checked";
                                                                        }
                                                                    }
                                                                    ?>>
                                                                <span class="slider round"></span>
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
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label for="if_static_cost" style="display: block;">If static price (PING & POST Only)</label>
                                                            <label class="form-group switch">
                                                                <input type="checkbox" name="if_static_cost" id="if_static_cost" value="1"
                                                                <?php
                                                                    if(old('if_static_cost') !== null){
                                                                        if(old('if_static_cost') == 1){
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
                                                            <label for="budget_bid_exclusive">Profit</label>
                                                            <input type="number" class="form-control" id="budget_bid_exclusive" name="budget_bid_exclusive" placeholder="%"
                                                                   value="{{ old('budget_bid_exclusive') }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="special_state">Special State</label>
                                                            <select class="select2 form-control select2-multiple" name="special_state[]" id="special_state" multiple="multiple" data-placeholder="Choose ...">
                                                                <optgroup label="States">
                                                                    @if( !empty($address['states']) )
                                                                        @foreach($address['states'] as $state)
                                                                            @if( empty(old('special_state')) )
                                                                                <option value="{{ $state->state_id }}">{{ $state->state_code }}</option>
                                                                            @else
                                                                                @if( in_array($state->state_id, old('special_state')) )
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
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="special_budget_bid_exclusive">Special Profit/Cost</label>
                                                            <input type="number" class="form-control" id="special_budget_bid_exclusive" name="special_budget_bid_exclusive" placeholder="%"
                                                                   @if(old('special_budget_bid_exclusive')) value="{{ old('special_budget_bid_exclusive') }}" @else value="0" @endif>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="special_source">Special Sources (separated by comas)</label>
                                                            <br>
                                                            <span>for example: source1,source2,source3</span>
                                                            <textarea class="form-control" id="special_source" name="special_source" placeholder="src"> @if(old('special_source')) {{ old('special_source') }} @endif   </textarea>

                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="special_source_price">Special Source Profit/Cost</label>
                                                            <br>
                                                            <br>
                                                            <input type="number" class="form-control" id="special_source_price" name="special_source_price" placeholder="%"
                                                                   @if(old('special_source_price')) value="{{ old('special_source_price') }}" @else value="0" @endif>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </fieldset>

                                    <button type="submit" class="btn btn-primary stepy-finish" id="submitFormCam">Submit</button>

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

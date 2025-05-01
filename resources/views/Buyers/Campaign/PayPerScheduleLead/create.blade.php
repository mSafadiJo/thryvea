@extends('layouts.NavBuyerHome')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Add Campaign Pay Per Schedule Lead</h4>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="wrapper-page wrapper-page-camp">
            <div class="account-pages AlliedAccount-Pages">
                <div class="account-box" style="max-width: unset;">
                    <div class="account-content">
                        <!-- Basic Form Wizard -->
                        <div class="row">
                            <div class="col-md-12">
                                <form id="default-wizard" action="{{ route('CampaignStor') }}" method="POST" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <fieldset title="1">
                                        <legend>Campaign Details</legend>

                                        <div class="row m-t-20">
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <div class="@if($type_id == "6") col-sm-4 @else col-sm-6 @endif">
                                                        <div class="form-group">
                                                            <label for="Campaign_name">Campaign Name<span class="requiredFields">*</span></label>
                                                            <input type="text" class="form-control" id="Campaign_name" name="Campaign_name" placeholder="" required=""
                                                                   value="{{ old('Campaign_name') }}">
                                                            <input type="hidden" name="type_id" required=""
                                                                   value="{{ $type_id }}">
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

                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="website_name">WebSite<span class="requiredFields">*</span></label>
                                                            <select id="propertytype" name="website[]" class="select2 form-control select2-multiple" required="" multiple="multiple" data-placeholder="Choose ...">
                                                                <optgroup label="Property Type">
                                                                    @if( !empty( $websites ) )
                                                                        @foreach( $websites as $website )
                                                                            <option value="{{ $website->domain_name }}">{{ $website->domain_name }}</option>
                                                                        @endforeach
                                                                    @endif
                                                                </optgroup>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="click_text">brand text<span class="requiredFields">*</span></label>
                                                            <input type="text" class="form-control" id="click_text" name="click_text" placeholder="" required
                                                                   value="{{ old('click_text') }}">
                                                        </div>
                                                    </div>

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
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                    <fieldset title="2">
                                        <legend>Campaign  Target</legend>

                                        <div class="row m-t-20">
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <h6>Choose a place among my working areas</h6>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="state">State</label>
                                                            <select class="select2 form-control select2-multiple stateCam" name="state[]" id="stateCampName" multiple="multiple" multiple data-placeholder="Choose ...">
                                                                <optgroup label="States">
                                                                    @if( !empty($address['states']) )
                                                                        @foreach($address['states'] as $state)
                                                                            @if( empty(old('state')) )
                                                                                <option value="{{ $state->state_id }}">{{ $state->state_code }}</option>
                                                                            @else
                                                                                @if( in_array($state->state_id, old('state')) )
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

                                                                </optgroup>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group city_filter">
                                                            <label for="city">City</label>
                                                            <select class="form-control city_filter" multiple name="city[]" id="City_Name">
                                                                <optgroup label="">

                                                                </optgroup>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group zip_filter">
                                                            <label for="zipcode">Zip-Code</label>
                                                            <select class="form-control zip_filter" multiple name="zipcode[]" id="Zip_Name" >
                                                                <optgroup label="">

                                                                </optgroup>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="zipcode">Zip-Code</label>
                                                            <input type="file" class="form-control" name="listOfzipcode" id="listOfzipcode" accept=".xls,.xlsx,.csv">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
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
                                                            <label for="city_expect city_filter_expect">City</label>
                                                            <select class="form-control city_filter" multiple name="city_expect[]" id="City_Name_expect">
                                                                <optgroup label="">
                                                                </optgroup>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group zip_filter_expect">
                                                            <label for="zipcode_expect zip_filter_expect">Zip-Code</label>
                                                            <select class="select2 form-control select2-multiple zip_filter_expect" multiple name="zipcode_expect[]" id="Zip_Name_expect" data-placeholder="Type more than two numbers ...">
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
                                        {{--Delivery Method--}}
                                        @include('include.Campaigns.add_delivery_methods')
                                        {{--End Delivery Method--}}
                                    </fieldset>

                                    <fieldset title="4">
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
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="budget_bid_shared">Budget Bid </label>
                                                            <input type="number" class="form-control" id="budget_bid_shared" name="budget_bid_shared" placeholder=""
                                                                   value="{{ old('budget_bid_shared') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </fieldset>

                                    <button type="submit" id="submitFormCam" class="btn btn-primary stepy-finish">Submit</button>
                                    {{--                                <button class="buttonloadCam btn btn-primary stepy-finish" disabled style="display: none">--}}
                                    {{--                                    <i class="fa fa-circle-o-notch fa-spin"></i>Loading--}}
                                    {{--                                </button>--}}
                                </form>
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

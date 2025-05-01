@extends('layouts.NavBuyerHome')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Add Campaign Pay Per Click</h4>
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
                                                    <input type="hidden" name="propertytype[]" value="1">
                                                    <input type="hidden" name="Installings[]" value="1">
                                                    <input type="hidden" name="homeowned[]" value="1">
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label for="Campaign_name">Campaign Name<span class="requiredFields">*</span></label>
                                                            <input type="text" class="form-control" id="Campaign_name" name="Campaign_name" placeholder="" required=""
                                                                   value="{{ old('Campaign_name') }}">
                                                            <input type="hidden" name="type_id" required=""
                                                                   value="{{ $type_id }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label for="landingPageUrl">landing page url<span class="requiredFields">*</span></label>
                                                            <input type="text" class="form-control" id="landingPageUrl" name="landingPageUrl" placeholder="" required
                                                                   value="{{ old('landingPageUrl') }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label for="click_text">brand text<span class="requiredFields">*</span></label>
                                                            <input type="text" class="form-control" id="click_text" name="click_text" placeholder="" required
                                                                   value="{{ old('click_text') }}">
                                                        </div>
                                                    </div>

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

                                                    <div class="col-sm-12">
                                                        <h6>Choose Budget Details</h6>
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
                                                            <select class="form-control city_filter" multiple name="city[]" id="City_Name">
                                                                <optgroup label="">

                                                                </optgroup>
                                                            </select>
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
                                                            {{-- <input type="button" class="clear_all" id="clear_all_expectCounty" value="Clear All">--}}
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group city_filter_expect">
                                                            <label for="City_Name">City Name</label>
                                                            <select class="form-control city_filter" multiple name="city_expect[]" id="City_Name_expect">
                                                                <optgroup label="">
                                                                </optgroup>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group zip_filter_expect">
                                                            <label for="City_Name">Zip Codes</label>
                                                            <select class="form-control zip_filter_expect" multiple name="zipcode_expect[]" id="Zip_Name_expect">
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

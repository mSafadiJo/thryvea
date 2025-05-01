@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Buyers Location Report</h4>
            </div>
        </div>
    </div>
    @php
        $permission_users = array();
        if( !empty(Auth::user()->permission_users) ){
            $permission_users = json_decode(Auth::user()->permission_users, true);
        }
    @endphp
    <link rel="stylesheet" href="https://unpkg.com/balloon-css/balloon.min.css">

    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <form action="{{ route('BuyersLocationReport.getData') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="service_id">Service Name</label>
                                <select class="select2 form-control" name="service_id" id="service_id" data-placeholder="Choose ...">
                                    <optgroup label="Service">
                                        @if( !empty( $services ) )
                                            <option class="placeHolderSelect" value="" disabled selected>Please Choose</option>
                                            @foreach( $services as $service )
                                                <option value="{{ $service->service_campaign_id }}">{{ $service->service_campaign_name }}</option>
                                            @endforeach
                                        @endif
                                    </optgroup>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="campaignTypes">Campaign Type</label>
                                <select class="select2 form-control" name="campaignTypes" id="campaignTypes" data-placeholder="Choose ...">
                                    <optgroup label="Campaign Type">
                                        @if( !empty( $campaignTypes ) )
                                            <option class="placeHolderSelect" value="" disabled selected>Please Choose</option>
                                            @foreach( $campaignTypes as $campaignType )
                                                <option value="{{ $campaignType->campaign_types_id }}">{{ $campaignType->campaign_types_name }}</option>
                                            @endforeach
                                        @endif
                                    </optgroup>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user_type">User Type</label>
                                <select class="select2 form-control select2-multiple" multiple name="user_type[]" id="user_type" data-placeholder="Choose ...">
                                    <optgroup label="User Type">
                                        <option value="3">Buyer</option>
                                        <option value="4">Aggregator</option>
                                        <option value="6">Enterprise</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="campaignStatus">Campaign Status</label>
                                <select class="select2 form-control" name="campaignStatus" id="campaignStatus" data-placeholder="Choose ...">
                                    <optgroup label="Campaign Status">
                                        <option value="All" selected>All</option>
                                        <option value="Running">Running</option>
                                        <option value="Pause">Pause</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="depending_on">Depending on</label>
                                <select class="select2 form-control" name="depending_on" id="depending_on" data-placeholder="Choose ...">
                                    <optgroup label="Depending on">
                                        <option value="State" selected>State</option>
                                        <option value="County">County</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label for="buyer_id">.</label>
                            <button type="submit" class="btn btn-primary col-lg-12" id="FilterZipCodeByServiceAjax">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Of Page 1-->
@endsection

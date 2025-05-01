@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Filter Zipcode From Campaigns By Service</h4>
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
    {{--Loader--}}
    <div class="loader" style="display: none;"></div>

    <div class="un_loading_loader">
        <div class="row">
            <div class="col-lg-12">
                <div class="card-box">
                    <form action="{{ route('ExportlistZipCodeByService') }}" method="POST">
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
                                    <label for="platform_id">Platform Name</label>
                                    <select class="select2 form-control" name="platform_id" id="platform_id" data-placeholder="Choose ...">
                                        <optgroup label="Platform">
                                            @if( !empty( $Platforms ) )
                                                <option class="placeHolderSelect" value="" disabled selected>Please Choose</option>
                                                @foreach( $Platforms as $Platform )
                                                    <option value="{{ $Platform->name }}">{{ $Platform->name }}</option>
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="Campaign Type">Campaign Type</label>
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
                                    <label for="User_Type">User Type</label>
                                    <select class="select2 form-control select2-multiple" multiple name="user_type[]" id="user_type" data-placeholder="Choose ...">
                                        <optgroup label="User Type">
                                            <option value="3"> Buyer </option>
                                            <option value="4"> Aggregator </option>
                                            <option value="6"> Enterprise </option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="Campaign Type">Campaign Status</label>
                                    <select class="select2 form-control" name="campaignStatus" id="campaignStatus" data-placeholder="Choose ...">
                                        <optgroup label="Campaign Status">
                                            <option  value="All" selected >All</option>
                                            <option  value="Running" >Running</option>
                                            <option  value="Pause" >Pause</option>
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
    </div>

    <!-- End Of Page 1-->
@endsection

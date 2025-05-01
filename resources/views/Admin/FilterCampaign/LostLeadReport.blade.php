@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Lost Leads Report</h4>
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
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="service_id">Campaign Name</label>
                                        <select class="form-control select2" name="campaign_id" id="campaign_id" data-placeholder="Choose ...">
                                            <optgroup label="Campaign">
                                                @if( !empty( $campaign ) )
                                                    <option class="placeHolderSelect" value="" disabled selected>Please Choose</option>
                                                    @foreach( $campaign as $campaigns )
                                                        <option value="{{ $campaigns->campaign_id }}">{{ $campaigns->campaign_name }} ({{ $campaigns->user_business_name }})</option>
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="Start Date">Start Date</label>
                                            <input type="text" id="datepicker1" placeholder="From Date" autocomplete="false" class="form-control StartDate" value="{{ date('Y-m-d') }}"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="End Date">End Date</label>
                                            <input type="text" id="datepicker2" placeholder="To Date" autocomplete="false" class="form-control EndDate" value="{{ date('Y-m-d') }}"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-primary col-lg-12" id="FilterLeadLostReport">Submit</button>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div style="margin-bottom: 5%;"></div>
                    <!--Panel heading-->
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="dataAjaxTableCampaign">
                                <table class="table table-striped table-bordered" cellspacing="0" width="100%"
                                       @if( empty($permission_users) || in_array('3-4', $permission_users) ) id="datatable-buttons" @else id="datatable" @endif>
                                    <thead>
                                    <tr>
                                        <th>lead Id</th>
                                        <th>Lead Name</th>
                                        <th>Source</th>
                                        <th>Reason</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Of Page 1-->
    @include('include.include_reports')
@endsection

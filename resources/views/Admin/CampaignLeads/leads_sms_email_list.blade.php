@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Leads</h4>
            </div>
        </div>
    </div>
    @php
        $permission_users = array();
        if( !empty(Auth::user()->permission_users) ){
            $permission_users = json_decode(Auth::user()->permission_users, true);
        }
    @endphp
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="service_id">Service Name</label>
                                    <select class="select2 form-control select2-multiple" multiple name="service_id[]" id="service_id" data-placeholder="Choose ...">
                                        <optgroup label="Service">
                                            @if( !empty( $data['services'] ) )
                                                @foreach( $data['services'] as $service )
                                                    <option value="{{ $service->service_campaign_id }}">{{ $service->service_campaign_name }}</option>
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="buyer_id">State Name</label>
                                    <select class="select2 form-control select2-multiple" name="states[]" id="statenamelead" multiple="multiple" data-placeholder="Choose ...">
                                        <optgroup label="States">
                                            @if( !empty($data['states']) )
                                                @foreach($data['states'] as $state)
                                                    <option value="{{ $state->state_id }}">{{ $state->state_code }}</option>
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <input type="text" id="datepicker1" name="start_date" placeholder="From Date" value="{{ date('Y-m') }}-1" autocomplete="false" class="form-control"/>
                            </div>
                            <div class="col-lg-4">
                                <input type="text" id="datepicker2" name="end_date" placeholder="To Date" value="{{ date('Y-m-t') }}" autocomplete="false" class="form-control"/>
                            </div>
                            <div class="col-lg-4">
                                <div class="row">
                                    <button type="button" class="btn btn-primary col-lg-12" id="filterLeadSMSEmail">Search</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div style="margin-bottom: 5%;"></div>
                <h4 class="header-title">List Of Leads</h4>
                <div class="row">
                    <div class="col-lg-12">
                        <div id="dataAjaxTableCampaign">
                            <table id="datatable4" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Lead Name</th>
                                    <th>State</th>
                                    <th>Service</th>
                                    <th>Source</th>
                                    <th>TS</th>
                                    <th>SoldTo</th>
                                    <th>type</th>
                                    <th>Bid</th>
                                    <th>Sold Date</th>
                                    <th>Created At</th>
                                    @if( empty($permission_users) || in_array('8-10', $permission_users) )
                                        <th>Action</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Of Page 1-->
    @include('include.include_reports')
@endsection

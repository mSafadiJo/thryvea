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
                        <form action="{{ route('export_lead_data') }}" method="POST">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="service_id">Service Name</label>
                                        <select class="select2 form-control select2-multiple" multiple
                                                name="service_id[]" id="service_id" data-placeholder="Choose ...">
                                            <optgroup label="Service">
                                                @if( !empty( $data['services'] ) )
                                                    @foreach( $data['services'] as $service )
                                                        <option
                                                            value="{{ $service->service_campaign_id }}">{{ $service->service_campaign_name }}</option>
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="buyer_id">Buyers</label>
                                        <select class="select2 form-control select2-multiple" multiple name="buyer_id[]"
                                                id="buyer_id" data-placeholder="Choose ...">
                                            <optgroup label="Buyer Name">
                                                @if( !empty( $data['buyers'] ) )
                                                    @foreach( $data['buyers'] as $buyer )
                                                        <option
                                                            value="{{ $buyer->id }}">{{ $buyer->user_business_name }}</option>
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="buyer_id">Sellers</label>
                                        <select class="select2 form-control select2-multiple" multiple
                                                name="seller_id[]" id="seller_id" data-placeholder="Choose ...">
                                            <optgroup label="Buyer Name">
                                                @if( !empty( $data['sellers'] ) )
                                                    @foreach( $data['sellers'] as $buyer )
                                                        <option
                                                            value="{{ $buyer->id }}">{{ $buyer->user_business_name }}</option>
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="buyer_id">State Name</label>
                                        <select class="select2 form-control select2-multiple" name="states[]"
                                                id="statenamelead" multiple="multiple" data-placeholder="Choose ...">
                                            <optgroup label="States">
                                                @if( !empty($data['states']) )
                                                    @foreach($data['states'] as $state)
                                                        <option
                                                            value="{{ $state->state_id }}">{{ $state->state_code }}</option>
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <input type="text" id="datepicker1" name="start_date" placeholder="From Date" value="{{ date('Y-m-d', strtotime('-1 days')) }}" autocomplete="false" class="form-control start_date_pagination"/>
                                </div>
                                <div class="col-lg-4">
                                    <input type="text" id="datepicker2" name="end_date" placeholder="To Date" value="{{ date('Y-m-d') }}" autocomplete="false" class="form-control end_date_pagination"/>
                                </div>
                                <div class="col-lg-4">
                                    <div class="row">
                                        @if( empty($permission_users) || in_array('8-4', $permission_users) )
                                            <div class="col-lg-6">
                                                <button type="button" class="btn btn-primary col-lg-12" id="filterLeadTablesNew">Search</button>
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="hidden" name="type" value="1">
                                                <button type="submit" class="btn btn-primary col-lg-12">Export</button>
                                            </div>
                                        @else
                                            <div class="col-lg-12">
                                                <button type="button" class="btn btn-primary col-lg-12" id="filterLeadTablesNew">Search</button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <hr>
                <div style="margin-bottom: 5%;"></div>
                <h4 class="header-title">List Of Sold Leads</h4>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="text" name="search" id="searchNew" class="form-control" placeholder="Search" style="width: 22%;float:right;margin-bottom:1%;"/>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div id="table_dataNew">
                            <div class="table-responsiveNew">
                                <table id="pagination-table" class="table table-striped table-bordered"
                                       data-action="/LeadReceived/fetch_data?page=">
                                    <thead>
                                    <tr>
                                        <th>Unique ID</th>
                                        <th>Lead ID</th>
                                        <th class="sorting" data-sorting="DESC" data-column_name="leads_customers-lead_fname" style="cursor: pointer">Lead Name <span id="leads_customers-lead_fname_icon"><i class="fa fa-arrow-down" aria-hidden="true"></i></span></th>
                                        <th>State</th>
                                        <th>SoldTo</th>
                                        <th>Campaign Name</th>
                                        <th>Service</th>
                                        <th>Source</th>
                                        <th>TS</th>
                                        <th>type</th>
                                        <th class="sorting" data-sorting="DESC" data-column_name="campaigns_leads_users-campaigns_leads_users_bid" style="cursor: pointer">Bid <span id="campaigns_leads_users-campaigns_leads_users_bid_icon"><i class="fa fa-arrow-down" aria-hidden="true"></i></span></th>
                                        <th class="sorting" data-sorting="DESC" data-column_name="campaigns_leads_users-campaigns_leads_users_note" style="cursor: pointer">Status <span id="campaigns_leads_users-campaigns_leads_users_note_icon"><i class="fa fa-arrow-down" aria-hidden="true"></i></span></th>
                                        <th>Trusted Form URL</th>
                                        <th class="sorting" data-sorting="DESC" data-column_name="campaigns_leads_users-created_at" style="cursor: pointer">Date <span id="campaigns_leads_users-created_at_icon"><i class="fa fa-arrow-down" aria-hidden="true"></i></span></th>
                                        @if( empty($permission_users) || in_array('8-10', $permission_users) || in_array('8-3', $permission_users)
                                            || in_array('8-11', $permission_users) )
                                            <th>Action</th>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @include('Render.Lead_Received_Render')
                                    </tbody>
                                </table>
                                <input type="hidden" name="hidden_page" id="hidden_page" value="1"/>
                                <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="campaigns_leads_users-created_at" />
                                <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="DESC" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Of Page 1-->
@endsection

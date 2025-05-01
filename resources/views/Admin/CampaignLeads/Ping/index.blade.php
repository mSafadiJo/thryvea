@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">PING Leads</h4>
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
                        <form action="{{ route('export_lead_data') }}" method="POST" id="LeadPingsForm">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-lg-3">
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
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="buyer_id">Sellers</label>
                                        <select class="select2 form-control select2-multiple" multiple name="seller_id[]" id="seller_id" data-placeholder="Choose ...">
                                            <optgroup label="Buyer Name">
                                                @if( !empty( $data['sellers'] ) )
                                                    @foreach( $data['sellers'] as $buyer )
                                                        <option value="{{ $buyer->id }}">{{ $buyer->user_business_name }}</option>
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3">
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
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="environments">Environments</label>
                                        <select class="select2 form-control" name="environments" id="environments" data-placeholder="Choose ...">
                                            <optgroup label="Environments">
                                                <option value="2" selected>Live</option>
                                                <option value="1">Test</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <input type="text" id="datepicker1" name="start_date" placeholder="From Date" autocomplete="false" class="form-control start_date_pagination"
                                           value="{{ date('Y-m-d') }}"/>
                                </div>
{{--                                <div class="col-lg-4">--}}
{{--                                    <input type="text" id="datepicker2" name="end_date" placeholder="To Date" autocomplete="false" class="form-control end_date_pagination"--}}
{{--                                           value="{{ date('Y-m-d') }}"/>--}}
{{--                                </div>--}}
                                <div class="col-lg-6">
                                    <div class="row">
                                        @if( empty($permission_users) || in_array('8-4', $permission_users) )
                                            <div class="col-lg-6">
                                                {{--filterLeadPings--}}
                                                <button type="button" class="btn btn-primary col-lg-12" id="filterLeadTables" data-type="1">Search</button>
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="hidden" name="type" value="8">
                                                <button type="submit" class="btn btn-primary col-lg-12" data-type="2">Export</button>
                                            </div>
                                        @else
                                            <div class="col-lg-12">
                                                <button type="button" class="btn btn-primary col-lg-12" id="filterLeadTables" data-type="1">Search</button>
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
                <h4 class="header-title">List Of PING Leads</h4>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="text" name="search" id="search" class="form-control" placeholder="Search" style="width: 22%;float:right;margin-bottom:1%;"/>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div id="table_data">
                            <div class="table-responsive">
                                <table id="pagination-table" class="table table-striped table-bordered" data-action="/LeadPing/fetch_data?page=">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Vendor ID</th>
                                        <th>State</th>
                                        <th>Seller Name</th>
                                        <th>Campaign Name</th>
                                        <th>Service</th>
                                        <th>Status</th>
                                        <th>Type</th>
                                        <th>Price</th>
                                        <th>Accepted By</th>
                                        <th>Trusted Form URL</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
{{--                                @foreach ($ListOfLeads as $ListLead)--}}
{{--                                    <tr>--}}
{{--                                        <td> {{$ListLead->lead_id}} </td>--}}
{{--                                        <td> {{$ListLead->vendor_id}}</td>--}}
{{--                                        <td> {{$ListLead->state_code}} </td>--}}

{{--                                        <td> {{$ListLead->user_business_name}} </td>--}}
{{--                                        <td> {{$ListLead->campaign_name}}</td>--}}
{{--                                        <td> {{$ListLead->service_campaign_name}} </td>--}}

{{--                                        <td> {{$ListLead->status}} </td>--}}
{{--                                        <td> {{$ListLead->lead_bid_type}}</td>--}}
{{--                                        <td> {{$ListLead->price}} </td>--}}

{{--                                        <td> {{$ListLead->buyer_camp_names}} </td>--}}
{{--                                        <td> @if( !empty($ListLead->trusted_form) )--}}
{{--                                                <a href='{{$ListLead->trusted_form}}' target='_blank'>Trusted Form</a>--}}
{{--                                            @endif--}}
{{--                                        </td>--}}
{{--                                        <td> {{$ListLead->created_at}} </td>--}}

{{--                                        @if( empty($permission_users) || in_array('8-10', $permission_users) || in_array('8-3', $permission_users)--}}
{{--                                        || in_array('8-11', $permission_users) )--}}
{{--                                          @if( empty($permission_users) || in_array('8-10', $permission_users) )--}}
{{--                                                <td>--}}
{{--                                           <a href="/Admin/lead/{{$ListLead->lead_id}}/details/PING" class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Details" data-trigger="hover" data-animation="false">--}}
{{--                                            <i class="mdi mdi-file-document-box font-18 vertical-middle m-r-10"></i>--}}
{{--                                           </a>--}}
{{--                                                </td>--}}
{{--                                            @endif--}}
{{--                                        @endif--}}
{{--                                    </tr>--}}
{{--                                @endforeach--}}
                                </tbody>
                            </table>
{{--                                Showing {{($ListOfLeads->currentPage()-1)* $ListOfLeads->perPage()+($ListOfLeads->total() ? 1:0)}} to {{($ListOfLeads->currentPage()-1)*$ListOfLeads->perPage()+count($ListOfLeads)}}  of  {{$ListOfLeads->total()}}  Results--}}
{{--                                {!! $ListOfLeads->links() !!}--}}
{{--                                <input type="hidden" name="hidden_page" id="hidden_page" value="1" />--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Of Page 1-->
@endsection

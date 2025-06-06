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
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="service_id">Service Name</label>
                                        <select class="select2 form-control select2-multiple" multiple name="service_id[]" id="service_id" data-placeholder="Choose ...">
                                            <optgroup label="Servive">
                                                @if( !empty( $data['services'] ) )
                                                    @foreach( $data['services'] as $service )
                                                        <option value="{{ $service->service_campaign_id }}">{{ $service->service_campaign_name }}</option>
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="buyer_id">Buyers</label>
                                        <select class="select2 form-control select2-multiple" multiple name="buyer_id[]" id="buyer_id" data-placeholder="Choose ...">
                                            <optgroup label="Buyer Name">
                                                @if( !empty( $data['buyers'] ) )
                                                    @foreach( $data['buyers'] as $buyer )
                                                        <option value="{{ $buyer->id }}">{{ $buyer->user_business_name }}</option>
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
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
                                    <input type="text" id="datepicker1" name="start_date" placeholder="From Date" value="{{ date('Y-m-d', strtotime('-1 days')) }}" autocomplete="false" class="form-control start_date_pagination"/>
                                </div>
                                <div class="col-lg-4">
                                    <input type="text" id="datepicker2" name="end_date" placeholder="To Date" value="{{ date('Y-m-d') }}" autocomplete="false" class="form-control end_date_pagination"/>
                                </div>
                                <div class="col-lg-4">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <button type="button" class="btn btn-primary col-lg-12" id="filterLeadTables">Search</button>
                                        </div>
                                        @if( empty($permission_users) || in_array('8-4', $permission_users) )
                                            <div class="col-lg-6">
                                                <input type="hidden" name="type" value="3">
                                                <button type="submit" class="btn btn-primary col-lg-12">Export</button>
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
                <h4 class="header-title">List Of Refund Leads</h4>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="text" name="search" id="search" class="form-control" placeholder="Search" style="width: 22%;float:right;margin-bottom:1%;"/>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div id="table_data">
                            <div class="table-responsive">
                                <table id="pagination-table" class="table table-striped table-bordered"
                                       data-action = "/LeadRefund/fetch_data?page=">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Lead Name</th>
                                    <th>State</th>
                                    <th>SoldTo</th>
                                    <th>Campaign Name</th>
                                    <th>Service</th>
                                    <th>Source</th>
                                    <th>type</th>
                                    <th>Bid</th>
                                    <th>Sold Date</th>
                                    <th>Return Date</th>
                                    @if( empty($permission_users) || in_array('8-10', $permission_users) )
                                        <th>Action</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($ListOfLeads as $campaignlead)
                                    <tr>
                                        <td> {{$campaignlead->campaigns_leads_users_id}} </td>
                                        <td> {{$campaignlead->lead_fname}} {{$campaignlead->lead_lname}} </td>
                                        <td> {{$campaignlead->state_code}} </td>
                                        <td> {{$campaignlead->user_business_name}} </td>
                                        <td> {{$campaignlead->campaign_name}} </td>
                                        <td> {{$campaignlead->service_campaign_name}} </td>
                                        <td> {{$campaignlead->lead_source_text}}
                                            @if( $campaignlead->converted == 1 )
                                             > R
                                            @endif
                                        </td>
                                        <td> {{$campaignlead->campaigns_leads_users_type_bid}} </td>
                                        <td> {{$campaignlead->campaigns_leads_users_bid}} </td>
                                        <td> {{date('m/d/Y', strtotime($campaignlead->created_at_lead))}} </td>
                                        <td> {{date('m/d/Y', strtotime($campaignlead->created_at))}} </td>
                                        @if( empty($permission_users) || in_array('8-10', $permission_users) )
                                            <td>
                                                <a href="/Admin/lead/{{$campaignlead->campaigns_leads_users_id}}/details/Return" class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Details" data-trigger="hover" data-animation="false">
                                                    <i class="mdi mdi-file-document-box font-18 vertical-middle m-r-10"></i>
                                                </a>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                                Showing {{($ListOfLeads->currentPage()-1)* $ListOfLeads->perPage()+($ListOfLeads->total() ? 1:0)}} to {{($ListOfLeads->currentPage()-1)*$ListOfLeads->perPage()+count($ListOfLeads)}}  of  {{$ListOfLeads->total()}}  Results
                                {!! $ListOfLeads->links() !!}
                                <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Of Page 1-->
@endsection
